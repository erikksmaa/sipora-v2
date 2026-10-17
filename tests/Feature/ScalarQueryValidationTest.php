<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

final class ScalarQueryValidationTest extends TestCase
{
    use RefreshDatabase;

    public static function malformedFilters(): array
    {
        return [
            ['/activities', 'q'], ['/activities', 'category'], ['/activities', 'location'],
            ['/communities', 'q'], ['/communities', 'category'],
            ['/opportunities', 'q'], ['/opportunities', 'category'],
            ['/programs', 'q'], ['/programs', 'category'],
            ['/youth', 'q'], ['/youth', 'area'], ['/search', 'q'],
            ['/statistics', 'age'], ['/statistics', 'district'],
            ['/forum', 'category'], ['/feed', 'tab'], ['/activities', 'page'],
        ];
    }

    #[DataProvider('malformedFilters')]
    public function test_array_filters_are_rejected_without_server_errors(string $path, string $key): void
    {
        $url = $path.'?'.http_build_query([$key => ['nested' => 'web']]);
        $this->getJson($url)->assertUnprocessable()->assertJsonValidationErrors($key);
        $this->get($url)->assertUnprocessable()->assertSee('Format parameter pencarian atau filter tidak valid.');
    }

    public function test_normal_empty_special_character_and_paginated_search_remain_usable(): void
    {
        foreach (['', 'web', '%_<>"&', 'tidak-ada-hasil-qa'] as $term) {
            $this->get('/activities?'.http_build_query(['q' => $term, 'page' => 1]))->assertOk();
        }
    }
}
