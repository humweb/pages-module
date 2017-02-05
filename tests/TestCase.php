<?php

namespace Humweb\Tests\Pages;

use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    protected $runMigrations = true;


    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        // Setup factories
        $this->withFactories(__DIR__.'/../resources/database/factories');

        if ($this->runMigrations === true) {
            $this->loadMigrationsFrom([
                '--database' => 'testing',
                '--realpath' => realpath(__DIR__.'/../resources/database/migrations'),
            ]);

        }
    }


    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
    }
}