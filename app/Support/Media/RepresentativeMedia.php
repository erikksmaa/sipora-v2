<?php

namespace App\Support\Media;

final class RepresentativeMedia
{
    public static function url(string $domain, string $key = ''): string
    {
        $domain = in_array($domain, ['activities', 'opportunities', 'programs', 'about', 'contact', 'organizations'], true) ? $domain : 'programs';
        $variant = (hexdec(substr(hash('sha256', $key), 0, 6)) % 3) + 1;

        return asset("media/phase23c/{$domain}/scene-{$variant}.svg");
    }
}
