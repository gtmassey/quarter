<?php

namespace Gtmassey\Quarter\Tests;

use Gtmassey\Period\PeriodServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            PeriodServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app) {}
}
