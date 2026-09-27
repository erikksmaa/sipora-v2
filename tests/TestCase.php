<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        $app = parent::createApplication();
        if ($app['config']->get('database.default') !== 'mysql'
            || $app['config']->get('database.connections.mysql.database') !== 'sipora_v2_testing') {
            throw new \RuntimeException('Tests may only run against the dedicated sipora_v2_testing MySQL database. Clear cached config first.');
        }

        return $app;
    }
}
