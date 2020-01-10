<?php

namespace JustusTheis\Adyatan\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use JustusTheis\Adyatan\AdyatanServiceProvider;

abstract class TestCase extends BaseTestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }


    /**
     * Load package service provider
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [AdyatanServiceProvider::class];
    }
}

