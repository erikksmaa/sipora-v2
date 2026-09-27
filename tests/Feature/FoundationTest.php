<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\BinaryUuid;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FoundationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Http::preventStrayRequests();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_public_home_and_authentication_views_render(): void
    {
        foreach (['/', '/login', '/register', '/forgot-password', '/reset-password/example'] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_mysql_schema_and_references_use_binary_ids(): void
    {
        $columns = DB::select("SELECT TABLE_NAME, COLUMN_NAME, COLUMN_TYPE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND ((TABLE_NAME = 'users' AND COLUMN_NAME = 'id') OR (TABLE_NAME = 'sessions' AND COLUMN_NAME = 'user_id') OR (TABLE_NAME = 'user_social_accounts' AND COLUMN_NAME IN ('id','user_id')) OR (TABLE_NAME IN ('model_has_roles','model_has_permissions') AND COLUMN_NAME = 'model_id') OR (TABLE_NAME = 'activity_log' AND COLUMN_NAME IN ('subject_id','causer_id')))");
        $this->assertCount(8, $columns);
        foreach ($columns as $column) {
            $this->assertSame('binary(16)', $column->COLUMN_TYPE);
        }
        $wrongDates = DB::select("SELECT TABLE_NAME, COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND DATA_TYPE IN ('datetime','timestamp') AND (DATA_TYPE <> 'datetime' OR DATETIME_PRECISION <> 6)");
        $this->assertSame([], $wrongDates);
        $this->assertSame('UTC', config('app.timezone'));
        $this->assertSame('Asia/Jakarta', config('sipora.display_timezone'));
    }

    public function test_user_round_trip_roles_permissions_and_reverse_relationships(): void
    {
        $user = User::factory()->create();
        $text = $user->uuid();
        $this->assertSame(16, strlen(DB::table('users')->value('id')));
        $this->assertSame($text, User::findOrFail(BinaryUuid::bytes($text))->uuid());
        $user->assignRole('admin');
        $this->assertTrue($user->fresh()->hasRole('admin'));
        $this->assertTrue($user->fresh()->can('verify identities'));
        $this->assertSame($text, Role::findByName('admin')->users->first()->uuid());
        $user->givePermissionTo('review activities');
        $this->assertTrue($user->fresh()->hasDirectPermission('review activities'));
        $user->revokePermissionTo('review activities');
        $this->assertFalse($user->fresh()->hasDirectPermission('review activities'));
        $user->removeRole('admin');
        $this->assertFalse($user->fresh()->hasRole('admin'));
    }

    public function test_seeders_are_idempotent_and_only_seed_three_global_roles(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $this->assertSame(['admin', 'verifier', 'youth'], Role::orderBy('name')->pluck('name')->all());
        $this->assertSame(15, Permission::count());
        $this->assertSame(0, Role::findByName('youth')->permissions()->count());
        $this->assertSame(0, User::count());
    }

    public function test_guests_cannot_access_any_workspace(): void
    {
        foreach (['/youth/home', '/admin/dashboard', '/verifier/dashboard'] as $path) {
            $this->get($path)->assertRedirect('/login');
        }
    }

    public function test_youth_has_no_government_workspace_access(): void
    {
        $user = User::factory()->create()->assignRole('youth');
        $this->actingAs($user)->get('/youth/home')->assertOk();
        $this->get('/admin/dashboard')->assertForbidden();
        $this->get('/verifier/dashboard')->assertForbidden();
        $this->assertFalse($user->can('manage users'));
    }

    public function test_government_workspaces_are_separate_and_require_verified_email(): void
    {
        $admin = User::factory()->create()->assignRole('admin');
        $this->actingAs($admin)->get('/admin/dashboard')->assertOk();
        $this->get('/verifier/dashboard')->assertForbidden();
        $verifier = User::factory()->create()->assignRole('verifier');
        $this->actingAs($verifier)->get('/verifier/dashboard')->assertOk();
        $this->get('/admin/dashboard')->assertForbidden();
        $verifier->forceFill(['email_verified_at' => null])->save();
        $this->get('/verifier/dashboard')->assertRedirect('/verify-email');
    }

    public function test_binary_ids_work_for_explicit_audit_subject_and_causer(): void
    {
        $user = User::factory()->create();
        $entry = activity()->causedBy($user)->performedOn($user)->event('foundation_test')->log('Test audit');
        $entry->refresh();
        $this->assertSame($user->getKey(), $entry->causer_id);
        $this->assertSame($user->uuid(), $entry->causer->uuid());
        $this->assertSame($user->uuid(), $entry->subject->uuid());
        $this->assertSame($user->uuid(), $entry->toArray()['subject_id']);
        $this->assertSame(1, DB::table('activity_log')->count());
    }

    public function test_social_account_fk_and_relationship_work(): void
    {
        $user = User::factory()->create();
        $account = $user->socialAccounts()->create([
            'provider' => 'google', 'provider_user_id' => 'test-provider', 'provider_email' => $user->email,
        ]);
        $this->assertSame($user->uuid(), $account->fresh()->user->uuid());
        $this->assertSame(16, strlen($account->getKey()));
        $this->expectException(QueryException::class);
        DB::table('user_social_accounts')->where('id', $account->getKey())->update(['user_id' => BinaryUuid::generate()]);
    }

    public function test_route_binding_and_queue_restoration_use_canonical_uuid(): void
    {
        $user = User::factory()->create();
        Route::middleware('web')->get('/_test/users/{user}', fn (User $user) => ['id' => $user->uuid()]);
        $this->get('/_test/users/'.$user->uuid())->assertJson(['id' => $user->uuid()]);
        $this->get('/_test/users/invalid')->assertNotFound();
        $restored = (new User)->newQueryForRestoration($user->getQueueableId())->firstOrFail();
        $this->assertSame($user->uuid(), $restored->uuid());
    }

    public function test_private_storage_has_no_public_serving_route_or_symlink(): void
    {
        $this->assertFalse(config('filesystems.disks.private.serve'));
        $this->assertSame('private', config('filesystems.disks.private.visibility'));
        $this->assertNotContains(config('filesystems.disks.private.root'), config('filesystems.links'));
        $this->assertFalse(is_link(public_path('private')));
        $this->get('/storage/private/identity.pdf')->assertNotFound();
        $this->get('/storage/identity.pdf')->assertNotFound();
    }
}
