@props(['action', 'recaptcha' => null])
@php($useRecaptcha = $recaptcha && config('recaptcha.enabled'))
<form method="post" action="{{ $action }}" class="space-y-5" @if($recaptcha === 'login') data-confirm="false" @endif
    @if($useRecaptcha)
        x-data="recaptchaForm(@js(config('recaptcha.site_key')), @js(config('recaptcha.actions.'.$recaptcha)))"
        @submit.prevent="submit"
    @endif>
    @csrf
    {{ $slot }}
    @if($useRecaptcha)
        <input type="hidden" name="recaptcha_token" x-ref="token">
        <p x-cloak x-show="error" x-text="error" role="alert" class="text-sm text-red-700"></p>
        <button type="submit" class="btn w-full" :disabled="busy" x-text="busy ? 'Memverifikasi…' : @js(trim((string) ($button ?? 'Lanjutkan')))">{{ $button ?? 'Lanjutkan' }}</button>
        <p class="text-xs text-slate-500">Dilindungi reCAPTCHA. Google <a href="https://policies.google.com/privacy" class="underline">Kebijakan Privasi</a> dan <a href="https://policies.google.com/terms" class="underline">Persyaratan Layanan</a> berlaku.</p>
        @once
            @push('head')
                @if(config('recaptcha.site_key'))
                    <script src="https://www.google.com/recaptcha/api.js?render={{ urlencode(config('recaptcha.site_key')) }}" defer></script>
                @endif
            @endpush
        @endonce
    @else
        <button type="submit" class="btn w-full">{{ $button ?? 'Lanjutkan' }}</button>
    @endif
</form>
