<?php

namespace InterNACHI\ModularLivewire\Support;

use Illuminate\Support\ServiceProvider;
use InterNACHI\Modular\PluginRegistry;
use InterNACHI\ModularLivewire\ModularLivewirePlugin;

class ModularLivewireServiceProvider extends ServiceProvider
{
	public function register(): void
	{
		$this->app->singleton(ModularLivewirePlugin::class);
		
		PluginRegistry::register(ModularLivewirePlugin::class);
	}
}
