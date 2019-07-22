<?php

namespace Digitalcloud\SMS\Tests;

use Digitalcloud\SMS\ServiceProvider;
use Faker\Factory as Faker;
use Orchestra\Testbench\TestCase as Orchestra;

/**
 * Class TestCase
 * @package Tests
 * @property \Faker\Generator $faker
 */
abstract class TestCase extends Orchestra
{
    protected $faker;

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan("migrate:refresh")->run();
        $this->faker = Faker::create();
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.key', 'base64:/XDKnyH+oCnQ1lkUfF9/qGF4Fi6T+dmKa5HB9jIu5sA=');
        $app['config']->set('database.default', 'pgsql');

        $app['config']->set('database.connections.pgsql', [
            'driver' => 'pgsql',
            'database' => 'modules',
            'username' => 'postgres',
            'password' => '123123'
        ]);


        $this->loadPackageConfiguration($app);
    }

    protected function loadPackageConfiguration($app)
    {
        $config = require __DIR__ . "/../config/sms.php";
        $app['config']->set('sms', $config);
    }
}
