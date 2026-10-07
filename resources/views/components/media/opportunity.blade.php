@props(['opportunity', 'compact' => false])
<div class="relative">
    <x-media.representative domain="opportunities" :identity="$opportunity->slug" :compact="$compact" label="Ilustrasi peluang pengembangan pemuda" />
    @if($opportunity->organization && $opportunity->organization->review_status === 'approved' && $opportunity->organization->operational_status === 'active')
        <x-media.community :community="$opportunity->organization" class="absolute bottom-3 right-3" />
    @endif
</div>

