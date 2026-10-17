@auth
    @php
        $accountUser = auth()->user();
        $isYouthAccount = $accountUser->hasRole('youth');
        $isAdminAccount = $accountUser->hasRole('admin');
        $isVerifierAccount = $accountUser->hasRole('verifier');
        $accountRole = $isAdminAccount ? 'Admin' : ($isVerifierAccount ? 'Verifier' : 'Youth');
        $accountPhoto = \App\Support\Media\UserAvatar::accountUrl($accountUser);
        $accountInitials = \App\Support\Media\UserAvatar::initials($accountUser->name);
        $accountCommunities = $isYouthAccount ? app(\App\Services\Community\ManagedCommunityService::class)->forUser($accountUser) : collect();
        $accountLinks = $isAdminAccount
            ? [['Akun & Keamanan', route('admin.account.password.edit')], ['Buka Admin Workspace', route('admin.dashboard')]]
            : ($isVerifierAccount
                ? [['Akun & Keamanan', route('verifier.account.password.edit')], ['Buka Verifier Workspace', route('verifier.dashboard')]]
                : [['Profil Saya', route('youth.profile.show')], ['Youth Passport', route('youth.passport.index')], ['Pengaturan Akun', route('youth.account.show')], ['Buka Youth Workspace', route('youth.home')]]);
    @endphp
    <div class="account-dropdown" x-data="{ accountOpen: false }" @click.outside="accountOpen = false"
        @keydown.escape.stop="accountOpen = false; $refs.accountTrigger.focus()">
        <button x-ref="accountTrigger" type="button" class="account-trigger" @click="accountOpen = !accountOpen"
            @keydown.arrow-down.prevent="accountOpen = true; $nextTick(() => $refs.accountPanel.querySelector('a')?.focus())"
            :aria-expanded="accountOpen" aria-controls="account-panel" aria-label="Akun {{ $accountUser->name }}">
            <span class="account-avatar" aria-hidden="true" x-data="{ photoFailed: false }">
                @if($accountPhoto)<img src="{{ $accountPhoto }}" alt="" x-show="!photoFailed" x-init="if ($el.complete && !$el.naturalWidth) photoFailed = true" x-on:error="photoFailed = true" class="size-full object-cover">@endif
                <span @if($accountPhoto) x-cloak x-show="photoFailed" @endif>{{ $accountInitials }}</span>
            </span>
            <span class="account-trigger-name">{{ $accountUser->name }}</span>
            <x-ui.icon name="chevron-down" class="size-4 shrink-0" />
        </button>
        <div x-ref="accountPanel" id="account-panel" x-cloak x-show="accountOpen" x-transition.opacity.duration.150ms class="account-panel">
            <div class="border-b border-border px-3 py-3"><strong class="block text-sm text-text-primary">{{ $accountUser->name }}</strong><span class="text-xs text-text-muted">{{ $accountRole }}</span></div>
            @foreach($accountLinks as [$label, $href])<a class="account-menu-link" href="{{ $href }}">{{ $label }}</a>@endforeach
            @if($accountCommunities->isNotEmpty())
                <p class="px-3 pb-1 pt-3 text-xs font-bold text-text-muted">Community Workspace</p>
                @foreach($accountCommunities as $accountCommunity)<a class="account-menu-link" href="{{ route('manager.dashboard', $accountCommunity) }}">{{ $accountCommunity->name }}</a>@endforeach
            @endif
            <a class="account-menu-link border-t border-border" href="{{ route('home') }}">Kembali ke Situs Publik</a>
            <form action="{{ route('logout') }}" method="post">@csrf<button type="submit" class="account-menu-link w-full text-left">Keluar</button></form>
        </div>
    </div>
@endauth
