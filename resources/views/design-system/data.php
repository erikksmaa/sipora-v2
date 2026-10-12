<?php

// Showcase-only fixtures. Never persisted; relations are assigned to avoid queries.
$model = static function (string $class, array $values, array $relations = []) {
    $instance = new $class;
    $instance->forceFill($values);
    foreach ($relations as $key => $value) {
        $instance->setRelation($key, $value);
    }

    return $instance;
};
$category = $model(\App\Models\ActivityCategory::class, ['name' => 'Teknologi & kreativitas']);
$area = $model(\App\Models\AdministrativeArea::class, ['name' => 'Pemalang']);
$community = $model(\App\Models\Organization::class, ['name' => 'Komunitas Kreatif Pemalang', 'slug' => 'showcase-community', 'description' => 'Ruang bertemu, belajar, dan berkarya bersama pemuda Pemalang.', 'review_status' => 'approved', 'operational_status' => 'active', 'active_members_count' => 48, 'public_activities_count' => 12], ['category' => $category, 'administrativeArea' => $area]);
$activity = $model(\App\Models\Activity::class, ['title' => 'Workshop Web Development', 'slug' => 'showcase-activity', 'start_at' => '2026-11-14 09:00:00', 'location_type' => 'offline', 'venue_name' => 'Creative Hub Pemalang', 'quota' => 40, 'accepted_participants_count' => 24], ['category' => $category, 'organization' => $community, 'administrativeArea' => $area]);
$opportunity = $model(\App\Models\Opportunity::class, ['title' => 'Program Magang Digital', 'slug' => 'showcase-opportunity', 'provider_name' => 'Mitra Ekosistem SIPORA', 'description' => 'Kembangkan keahlian dan pengalaman bersama mitra lokal.', 'location_text' => 'Pemalang', 'deadline_at' => '2026-12-01 12:00:00'], ['category' => $category, 'organization' => null, 'administrativeArea' => $area]);
$program = $model(\App\Models\Program::class, ['title' => 'Pemuda Digital 2026', 'slug' => 'showcase-program', 'description' => 'Belajar, berkolaborasi, dan membangun karya yang berdampak.', 'execution_status' => 'running', 'start_date' => '2026-10-01', 'end_date' => '2026-12-31', 'public_activities_count' => 8], ['category' => $category, 'organization' => $community]);
$profile = ['name' => 'Pemuda SIPORA', 'headline' => 'Belajar dan berkarya bersama', 'domicile' => 'Pemalang', 'photo_url' => null, 'bio' => 'Profil contoh untuk dokumentasi visual. Bukan data pengguna.', 'skills' => ['Desain', 'Web Development'], 'activities_count' => 8, 'communities_count' => 2, 'certificates_count' => 3, 'url' => '#section-19'];

$sections = [
    'Cover / Design Identity', 'Color System', 'Typography System', 'Spacing & Layout', 'Border / Radius / Shadow', 'Iconography', 'Pixel / Playground Assets', 'Button System', 'Form Controls', 'Badges & Status', 'Alert / Toast / Feedback', 'Card System', 'Navigation', 'Dropdown / Accordion / Menu', 'Filter & Search Patterns', 'Table System', 'Modal / Dialog', 'Public Page Patterns', 'Youth Workspace Patterns', 'Community Workspace Patterns', 'Government Workspace Patterns', 'Data Visualization System', 'Ecosystem Map UI', 'Forum UI', 'Social Feed UI', 'Light & Dark Mode', 'Responsive System', 'Interaction States', 'Motion / Animation', 'Accessibility', "Design Do / Don't", 'Sketch to Final', 'Screenshot Capture Mode',
];
$css = file_get_contents(resource_path('css/app.css'));
preg_match('/@theme\s*\{(.*?)\n\}/s', $css, $theme);
preg_match_all('/--color-([\w-]+):\s*(#[\da-fA-F]+);/', $theme[1], $matches, PREG_SET_ORDER);
$tokens = array_column($matches, 2, 1);
preg_match('/html\[data-theme="dark"\]\s*\{(.*?)\n\}/s', $css, $darkBlock);
preg_match_all('/--color-([\w-]+):\s*(#[\da-fA-F]+);/', $darkBlock[1], $matches, PREG_SET_ORDER);
$darkTokens = array_column($matches, 2, 1);
$palette = [
    'Flame · accent / CTA' => array_filter($tokens, fn ($key) => preg_match('/^accent-\d+$/', $key), ARRAY_FILTER_USE_KEY),
    'Slate · primary / identity' => array_filter($tokens, fn ($key) => preg_match('/^primary-\d+$/', $key), ARRAY_FILTER_USE_KEY),
    'Cream / surfaces' => array_filter($tokens, fn ($key) => str_starts_with($key, 'cream-') || in_array($key, ['bg', 'surface', 'surface-soft', 'surface-muted']), ARRAY_FILTER_USE_KEY),
    'Text / borders / interaction' => array_filter($tokens, fn ($key) => str_starts_with($key, 'text-') || str_starts_with($key, 'border') || in_array($key, ['ink', 'interactive']), ARRAY_FILTER_USE_KEY),
    'Semantic' => array_filter($tokens, fn ($key) => preg_match('/^(success|warning|danger|info)/', $key), ARRAY_FILTER_USE_KEY),
    'Dark · actual overrides' => $darkTokens,
];
$typeRows = [
    ['Display', 'font-display text-5xl font-bold tracking-tight', 'Pixelify Sans', '48 / 48', '700', '-0.025em', 'Build Your Youth Journey', 'components/public/page-intro.blade.php · sm:text-5xl'],
    ['H1', 'font-heading text-3xl font-bold tracking-tight', 'Pixelify Sans', '30 / 36', '700', '-0.025em', 'Jelajahi Ekosistem Pemuda', 'components/workspace/page-header.blade.php'],
    ['H2', 'font-heading text-2xl font-bold', 'Pixelify Sans', '24 / 32', '700', 'normal', 'Temukan Kesempatanmu', 'forum/index.blade.php'],
    ['H3', 'text-lg font-extrabold', 'Plus Jakarta Sans', '18 / 28', '800', 'normal', 'Belajar bersama komunitas', 'forum/index.blade.php'],
    ['H4 / card title', 'text-base font-bold', 'Plus Jakarta Sans', '16 / 24', '700', 'normal', 'Langkah kecil, dampak besar', 'components/ui/empty-state.blade.php · h3 element'],
    ['Body large', 'text-lg leading-7', 'Plus Jakarta Sans', '18 / 28', '400', 'normal', 'Bergabung bersama ekosistem pemuda Pemalang.', 'components/public/page-intro.blade.php · sm:text-lg'],
    ['Body', 'text-sm leading-6', 'Plus Jakarta Sans', '14 / 24', '400', 'normal', 'Temukan Activity, Community, dan Opportunity untuk bertumbuh.', 'components/discovery/community-card.blade.php'],
    ['Body small', 'text-xs font-semibold', 'Plus Jakarta Sans', '12 / 16', '600', 'normal', '48 anggota aktif · 12 Activity publik', 'components/discovery/community-card.blade.php'],
    ['Label', 'field-label', 'Plus Jakarta Sans', '14 / 20', '700', 'normal', 'Nama lengkap', 'app.css · .field-label'],
    ['Caption', 'text-xs font-bold uppercase tracking-wider', 'Plus Jakarta Sans', '12 / 16', '700', '0.05em', 'Teknologi & kreativitas', 'components/discovery/community-card.blade.php'],
    ['Metadata', 'text-xs text-text-muted', 'Plus Jakarta Sans', '12 / 16', '400', 'normal', '14 November 2026 · Pemalang', 'social/post-card.blade.php'],
    ['Button', 'text-sm font-extrabold leading-none', 'Plus Jakarta Sans', '14 / 14', '800', 'normal', 'Explore Activity', 'app.css · .btn-primary'],
    ['Mono', 'font-mono text-xs', 'JetBrains Mono', '12 / 16', '400 → fallback 500 loaded', 'normal', 'SIPORA-CERT-2026-001', 'youth/certificates/index.blade.php'],
    ['Pixel accent', 'text-score', 'Press Start 2P', '10 / 16.25', '400', '0.025em', 'LEVEL UP', 'app.css · .text-score'],
];
$iconSource = file_get_contents(resource_path('views/components/ui/icon.blade.php'));
preg_match_all('/^\s*\x27([\w-]+)\x27(?:,\s*\x27[\w-]+\x27)?\s*=>/m', $iconSource, $iconMatches);
$icons = array_values(array_filter($iconMatches[1], fn ($name) => $name !== 'default'));
$patterns = [
    18 => [['Landing', 'home.blade.php', 'hero'], ['Activity listing', 'public/activities/index.blade.php', 'listing'], ['Activity detail', 'public/activities/show.blade.php', 'detail'], ['Community listing', 'public/communities/index.blade.php', 'listing'], ['Community detail', 'public/communities/show.blade.php', 'detail'], ['Opportunity', 'public/opportunities/index.blade.php', 'listing'], ['Forum', 'forum/index.blade.php', 'forum'], ['Feed', 'social/index.blade.php', 'feed'], ['Statistics', 'public/statistics/index.blade.php', 'stats'], ['Ecosystem Maps', 'ecosystem-map/index.blade.php', 'map']],
    19 => [['Dashboard', 'youth/home.blade.php', 'youth-dashboard'], ['Profile readiness', 'components/profile-completion.blade.php', 'readiness'], ['Biodata', 'youth/biodata.blade.php', 'form'], ['Portfolio', 'portfolio/show.blade.php', 'detail'], ['Skill', 'youth/skills.blade.php', 'chips'], ['Interest', 'youth/interests.blade.php', 'chips'], ['Activity', 'youth/activities/index.blade.php', 'table'], ['Community', 'youth/communities/index.blade.php', 'listing'], ['Passport', 'youth/passport/index.blade.php', 'passport'], ['Certificate', 'youth/certificates/index.blade.php', 'certificate'], ['Development Pathway', 'youth/development-pathway/index.blade.php', 'progress'], ['Account settings', 'youth/account/show.blade.php', 'form']],
    20 => [['Dashboard', 'manager/dashboard.blade.php', 'queues'], ['Profile', 'manager/profile/edit.blade.php', 'form'], ['Members', 'manager/members/index.blade.php', 'table'], ['Join Request', 'manager/members/join-requests.blade.php', 'table'], ['Activity', 'manager/activities/index.blade.php', 'table'], ['Participants', 'manager/participants/index.blade.php', 'table'], ['Session', 'manager/activity-sessions/index.blade.php', 'session'], ['Attendance', 'manager/attendance/index.blade.php', 'table'], ['Program', 'manager/programs/index.blade.php', 'program-list'], ['Proposal', 'manager/program-proposals/form.blade.php', 'form'], ['Logbook', 'manager/program-logbooks/index.blade.php', 'logbook'], ['E-LPJ', 'manager/financial-reports/form.blade.php', 'form']],
    21 => [['Admin Dashboard', 'workspaces/admin.blade.php', 'stats'], ['Verifier Dashboard', 'workspaces/verifier.blade.php', 'queues'], ['Identity Review', 'admin/identity-verifications/show.blade.php', 'detail'], ['Program Verification', 'verifier/program-proposals/show.blade.php', 'detail'], ['Final Evaluation', 'verifier/program-evaluations/show.blade.php', 'form'], ['Analytics', 'workspaces/admin.blade.php', 'stats'], ['Forum Moderation', 'admin/forum/index.blade.php', 'table'], ['Feed Moderation', 'admin/social/index.blade.php', 'table']],
];

return compact('sections', 'tokens', 'darkTokens', 'palette', 'typeRows', 'icons', 'patterns', 'activity', 'community', 'opportunity', 'program', 'profile');
