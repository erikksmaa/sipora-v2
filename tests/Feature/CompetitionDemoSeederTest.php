<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\ActivityAttendance;
use App\Models\ActivityParticipation;
use App\Models\ForumReaction;
use App\Models\ForumReply;
use App\Models\ForumReport;
use App\Models\ForumThread;
use App\Models\Opportunity;
use App\Models\Organization;
use App\Models\OrganizationMembership;
use App\Models\Program;
use App\Models\SocialComment;
use App\Models\SocialFollow;
use App\Models\SocialLike;
use App\Models\SocialPost;
use App\Models\SocialReport;
use App\Models\UserCertificate;
use App\Models\UserNotification;
use App\Services\Activity\ActivityPassportService;
use App\Services\SocialFeedService;
use App\Services\Youth\DevelopmentPathwayService;
use App\Services\YouthStatisticsService;
use App\Support\Nik;
use Database\Seeders\CompetitionDemoSeeder;
use Database\Seeders\DemoYouthSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CompetitionDemoSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_is_connected_eligible_private_safe_and_repeatable(): void
    {
        Storage::fake('proposal_documents');
        $this->seed(CompetitionDemoSeeder::class);
        $users = DemoYouthSeeder::users();
        $this->assertCount(24, $users);
        $erik = $users->first();
        $this->assertSame('Erik Kusuma Rais', $erik->name);
        foreach ([Organization::class => 15, Activity::class => 15, Opportunity::class => 15, Program::class => 15, ForumThread::class => 15, ForumReply::class => 30, ForumReaction::class => 20, SocialPost::class => 15, SocialComment::class => 25, SocialLike::class => 30, SocialFollow::class => 15] as $model => $minimum) {
            $this->assertGreaterThanOrEqual($minimum, $model::count(), $model);
        }
        $this->assertSame(3, ForumReport::count());
        $this->assertSame(2, SocialReport::count());
        $this->assertTrue($users->contains(fn ($user) => ! $user->profileVisibility->is_profile_public));
        $this->assertTrue($users->contains(fn ($user) => $user->identity->verification_status === 'unverified'));
        $this->assertTrue($users->every(fn ($user) => Nik::validFormat($user->identity->nationalId()) && str_starts_with($user->identity->nationalId(), '000000')));
        $this->assertCount($users->count(), $users->map(fn ($user) => $user->identity->national_id_hash)->unique());
        $anchorHashes = $users->mapWithKeys(fn ($user) => [$user->uuid() => $user->identity->national_id_hash])->all();
        $this->assertTrue($users->every(fn ($user) => ! $user->hasRole('manager')));
        $entries = app(ActivityPassportService::class)->queryFor($erik)->get();
        $this->assertCount(6, $entries);
        $this->assertSame(5, $entries->filter(fn ($entry) => $entry->certificate !== null)->count());
        $bootcamp = $entries->firstWhere('activity.title', 'Bootcamp Desain Konten Digital');
        $this->assertSame(3, $bootcamp->present_count);
        $this->assertSame(1, $bootcamp->absent_count);
        $this->assertNull($bootcamp->certificate);
        $workshop = $entries->firstWhere('activity.title', 'Workshop Web Development untuk Pemuda');
        $this->assertSame('Pemalang Youth Digital Empowerment 2026', $workshop->activity->program->title);
        $this->assertSame(20, $workshop->activity->participations()->count());
        foreach ($workshop->activity->sessions()->orderBy('session_number')->get() as $i => $session) {
            $counts = $session->attendances()->selectRaw('attendance_status, count(*) as total')->groupBy('attendance_status')->pluck('total', 'attendance_status');
            $this->assertSame([16, 18, 17][$i], (int) $counts['present']);
            $this->assertSame([2, 1, 3][$i], (int) $counts['absent']);
            $this->assertSame([2, 1, 0][$i], (int) ($counts['excused'] ?? 0));
        }
        $pathway = app(DevelopmentPathwayService::class)->for($erik->fresh());
        foreach ($pathway['recommendations'] as $items) {
            $this->assertNotEmpty($items);
        }
        $this->assertGreaterThan(app(SocialFeedService::class)->paginate('following', $erik)->total(), app(SocialFeedService::class)->paginate('all', $erik)->total());
        $this->assertSame(24, app(YouthStatisticsService::class)->summary()['registered']);
        $this->actingAs($erik);
        foreach (['/feed', '/feed?tab=following', '/forum', '/activities', '/communities', '/opportunities', '/programs', '/youth/development-pathway', '/youth/passport', '/statistics', '/ecosystem-map'] as $url) {
            $this->get($url)->assertOk();
        }
        $models = [Activity::class, ActivityAttendance::class, ActivityParticipation::class, Organization::class, OrganizationMembership::class, Program::class, Opportunity::class, ForumThread::class, ForumReply::class, ForumReaction::class, ForumReport::class, SocialPost::class, SocialComment::class, SocialLike::class, SocialFollow::class, SocialReport::class, UserCertificate::class, UserNotification::class];
        $before = array_map(fn ($model) => $model::count(), $models);
        $this->seed(CompetitionDemoSeeder::class);
        $this->assertSame($before, array_map(fn ($model) => $model::count(), $models));
        $this->assertSame($anchorHashes, DemoYouthSeeder::users()->mapWithKeys(fn ($user) => [$user->uuid() => $user->identity->national_id_hash])->all());
    }

    public function test_production_guard_refuses_all_writes(): void
    {
        $this->app->detectEnvironment(fn () => 'production');
        $this->expectException(\RuntimeException::class);
        (new CompetitionDemoSeeder)->run();
    }
}
