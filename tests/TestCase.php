<?php

namespace InterNACHI\ModularLivewire\Tests;

use InterNACHI\Modular\Support\Facades\Modules;
use InterNACHI\Modular\Support\ModularizedCommandsServiceProvider;
use InterNACHI\Modular\Support\ModularServiceProvider;
use InterNACHI\ModularLivewire\Support\ModularLivewireServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
	protected function getPackageProviders($app)
	{
		return [
			ModularServiceProvider::class,
			ModularizedCommandsServiceProvider::class,
			LivewireServiceProvider::class,
			ModularLivewireServiceProvider::class,
		];
	}
	
	protected function getPackageAliases($app)
	{
		return [
			'Modules' => Modules::class,
		];
	}
}
