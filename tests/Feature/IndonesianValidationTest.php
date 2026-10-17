<?php

namespace Tests\Feature;

use App\Http\Requests\Manager\StoreActivityRequest;
use App\Http\Requests\Manager\StoreProgramRequest;
use App\Http\Requests\Youth\SubmitIdentityVerificationRequest;
use App\Models\Activity;
use App\Models\Organization;
use App\Models\Program;
use App\Models\User;
use App\Rules\BinaryUuidExists;
use App\Services\Activity\ActivityEligibilityService;
use App\Services\ChatService;
use App\Services\Program\ProgramIntakeService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class IndonesianValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['recaptcha.enabled' => false]);
        $this->seed(RolePermissionSeeder::class);
        Notification::fake();
    }

    public function test_default_locale_and_catalog_cover_installed_framework_rules(): void
    {
        $this->assertSame('id', app()->getLocale());
        $english = require base_path('vendor/laravel/framework/src/Illuminate/Translation/lang/en/validation.php');
        $indonesian = require lang_path('id/validation.php');
        unset($english['custom'], $english['attributes']);
        $this->assertSame([], array_diff(array_keys(Arr::dot($english)), array_keys(Arr::dot($indonesian))));
        $this->assertSame('Berikutnya &raquo;', __('pagination.next'));
        $this->assertSame('Kata sandi berhasil diperbarui.', __('passwords.reset'));
        $this->assertSame('Email atau kata sandi tidak sesuai.', __('auth.failed'));
    }

    public function test_auth_required_email_and_json_summary_keep_the_existing_structure(): void
    {
        $this->postJson(route('login.store'), [])->assertUnprocessable()
            ->assertJsonStructure(['message', 'errors' => ['email', 'password']])
            ->assertJsonPath('errors.email.0', 'Kolom email wajib diisi.')
            ->assertJsonPath('errors.password.0', 'Kolom kata sandi wajib diisi.')
            ->assertJsonPath('message', 'Kolom email wajib diisi. (dan 1 kesalahan lainnya)');
        $this->postJson(route('password.email'), ['email' => 'bukan-email'])->assertUnprocessable()
            ->assertJsonPath('errors.email.0', 'Kolom email harus berupa alamat email yang valid.');
    }

    public function test_unique_email_confirmation_and_password_lengths_are_localized(): void
    {
        $user = User::factory()->create();
        $data = ['name' => 'Pemuda Uji', 'email' => $user->email, 'password' => 'kata-sandi-uji', 'password_confirmation' => 'berbeda'];
        $this->postJson(route('register.store'), $data)->assertUnprocessable()
            ->assertJsonPath('errors.email.0', 'email sudah digunakan.')
            ->assertJsonPath('errors.password.0', 'Konfirmasi kata sandi tidak cocok.');
        foreach (['abc' => 'minimal harus berisi 8 karakter', str_repeat('a', 73) => 'maksimal hanya boleh berisi 72 karakter'] as $password => $expected) {
            $data['email'] = 'tidak-terdaftar@example.test';
            $data['password'] = $data['password_confirmation'] = $password;
            $response = $this->postJson(route('register.store'), $data)->assertUnprocessable();
            $this->assertStringContainsString($expected, $response->json('errors.password.0'));
        }
    }

    public function test_upload_constraints_and_nested_attributes_have_readable_labels(): void
    {
        $request = new SubmitIdentityVerificationRequest;
        $request->merge(['document_type' => 'ktp']);
        $validator = Validator::make([
            'document_type' => 'ktp', 'document_number' => '123',
            'document_file' => UploadedFile::fake()->create('bukti.txt', 10, 'text/plain'),
        ], $request->rules(), $request->messages());
        $this->assertSame('Nomor KTP/KIA harus terdiri dari 16 digit.', $validator->errors()->first('document_number'));
        $this->assertSame('Berkas harus dalam format JPG/JPEG, PNG, atau PDF.', $validator->errors()->first('document_file'));
        $validator = Validator::make(['evidence' => UploadedFile::fake()->create('besar.pdf', 6000, 'application/pdf')], ['evidence' => ['file', 'max:5120']]);
        $this->assertSame('Ukuran berkas bukti pendukung maksimal 5120 KB.', $validator->errors()->first());
        $validator = Validator::make(['attendance' => ['participant-uuid' => ['status' => 'salah']]], ['attendance.*.status' => ['in:present,absent,excused']]);
        $this->assertSame('status presensi peserta yang dipilih tidak valid.', $validator->errors()->first());
        $validator = Validator::make(['organization_id' => 'bukan-uuid'], ['organization_id' => [new BinaryUuidExists('organizations')]]);
        $this->assertSame('Community yang dipilih tidak valid.', $validator->errors()->first());
    }

    public function test_program_and_activity_date_messages_use_labels_instead_of_payload_keys(): void
    {
        $rules = (new StoreProgramRequest)->rules();
        $validator = Validator::make(['start_date' => '2026-10-20', 'end_date' => '2026-10-19'], Arr::only($rules, ['start_date', 'end_date']));
        $this->assertSame('Kolom tanggal selesai harus berupa tanggal pada atau setelah tanggal mulai.', $validator->errors()->first());
        $request = new StoreActivityRequest;
        $route = (new Route('POST', '/test/{organization}', []))->bind(request()->create('/test/example', 'POST'));
        $route->setParameter('organization', new Organization);
        $request->setRouteResolver(fn () => $route);
        $rules = $request->rules();
        $validator = Validator::make(['start_at' => '2026-10-20', 'end_at' => '2026-10-19'], Arr::only($rules, ['start_at', 'end_at']));
        $this->assertSame('Kolom tanggal selesai harus berupa tanggal setelah tanggal mulai.', $validator->errors()->first());
        $this->assertSame('Pendaftaran Program tidak diaktifkan.', app(ProgramIntakeService::class)->blockingReason(null, new Program(['registration_enabled' => false])));
        try {
            app(ActivityEligibilityService::class)->ensureCanRegister(new User, new Activity);
            $this->fail('Expected the existing Activity eligibility rule to reject the request.');
        } catch (ValidationException $exception) {
            $this->assertSame('Activity tidak sedang menerima pendaftaran.', $exception->errors()['registration'][0]);
        }
    }

    public function test_chat_validation_keeps_json_keys_and_indonesian_messages(): void
    {
        $user = User::factory()->create()->assignRole('youth');
        $room = app(ChatService::class)->globalRoom();
        $this->actingAs($user)->postJson(route('chat.send', $room), ['message' => '  ', 'nonce' => (string) Str::uuid()])
            ->assertUnprocessable()->assertJsonPath('errors.message.0', 'Kolom pesan wajib diisi.');
        $this->postJson(route('chat.send', $room), ['message' => str_repeat('a', 1501), 'nonce' => (string) Str::uuid()])
            ->assertUnprocessable()->assertJsonPath('errors.message.0', 'Kolom pesan maksimal hanya boleh berisi 1500 karakter.');
    }

    public function test_reset_feedback_remains_generic_and_invalid_tokens_do_not_change_credentials(): void
    {
        $this->post(route('password.email'), ['email' => 'tidak-terdaftar@example.test'])
            ->assertRedirect()->assertSessionHas('status', 'Jika akun dengan email tersebut tersedia, tautan reset kata sandi telah dikirim. Periksa inbox dan folder spam.');
        Notification::assertNothingSent();
        $user = User::factory()->create();
        $original = $user->password;
        $this->postJson(route('password.update'), ['email' => $user->email, 'token' => 'invalid', 'password' => 'kata-sandi-uji', 'password_confirmation' => 'kata-sandi-uji'])
            ->assertUnprocessable()->assertJsonPath('errors.email.0', 'Tautan reset tidak valid atau telah kedaluwarsa. Silakan minta tautan baru.');
        $this->assertSame($original, $user->fresh()->password);
    }
}
