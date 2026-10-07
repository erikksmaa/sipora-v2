<?php

namespace App\Actions\Youth;

use App\Models\Interest;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

final class AddCustomPortfolioTagAction
{
    public function execute(User $user, string $kind, ?string $input): void
    {
        if (! filled($input)) {
            return;
        }

        $name = Str::squish(trim($input));
        $normalized = mb_strtolower($name);
        $field = $kind === 'skill' ? 'custom_skill' : 'custom_interest';
        $master = $kind === 'skill' ? Skill::class : Interest::class;
        if (preg_match('/[<>\x00-\x1F\x7F]/u', $name) || mb_strlen($name) < 2 || mb_strlen($name) > 120) {
            throw ValidationException::withMessages([$field => 'Isi pilihan lainnya dengan 2–120 karakter tanpa tanda HTML.']);
        }
        if ($master::query()->whereRaw('LOWER(name) = ?', [$normalized])->exists()
            || $user->customPortfolioTags()->where('kind', $kind)->where('normalized_name', $normalized)->exists()) {
            throw ValidationException::withMessages([$field => 'Pilihan ini sudah tersedia atau sudah kamu tambahkan.']);
        }

        $user->customPortfolioTags()->create(['kind' => $kind, 'name' => $name, 'normalized_name' => $normalized]);
    }
}
