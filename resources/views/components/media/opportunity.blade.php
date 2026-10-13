@props(['opportunity', 'compact' => false])
<div class="relative">
    <x-media.representative domain="opportunities" :identity="$opportunity->slug" :compact="$compact" label="Ilustrasi peluang pengembangan pemuda" />
    @php($organizerLogo = \App\Support\Demo\DemoAssetManifest::publicAsset('opportunities', $opportunity->slug, 'organizer_logo'))
    @if($organizerLogo)
        <img src="{{ $organizerLogo }}" alt="{{ $opportunity->provider_name }}" class="absolute bottom-3 right-3 size-12 rounded-xl bg-white p-2" loading="lazy">
    @elseif($opportunity->organization && $opportunity->organization->review_status === 'approved' && $opportunity->organization->operational_status === 'active')
        <x-media.community :community="$opportunity->organization" class="absolute bottom-3 right-3" />
    @endif
</div>

