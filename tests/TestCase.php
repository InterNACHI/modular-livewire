<?php

namespace Glhd\ModularLivewire\Tests;

use Glhd\ModularLivewire\Support\ModularLivewireServiceProvider;
use Illuminate\Container\Container;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
	protected function getPackageProviders($app)
	{
		return [
			ModularLivewireServiceProvider::class,
		];
	}
	
	protected function getPackageAliases($app)
	{
		return [];
	}
	
	protected function getApplicationTimezone($app)
	{
		return 'America/New_York';
	}
}
