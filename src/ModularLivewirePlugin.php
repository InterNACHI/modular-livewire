<?php

namespace InterNACHI\ModularLivewire;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use InterNACHI\Modular\Plugins\Plugin;
use InterNACHI\Modular\Support\FinderCollection;
use InterNACHI\Modular\Support\FinderFactory;
use InterNACHI\Modular\Support\ModuleFileInfo;
use InterNACHI\Modular\Support\ModuleRegistry;
use Livewire\Livewire;

class ModularLivewirePlugin extends Plugin
{
	public function __construct(
		protected ModuleRegistry $registry,
	) {
	}

	public static function boot(Closure $handler, Application $app): void
	{
		if (class_exists(Livewire::class)) {
			$handler(static::class);
		}
	}

	/**
	 * @return array<int, array{module: string, name: string, fqcn: string}>
	 */
	public function discover(FinderFactory $finders): iterable
	{
		return FinderCollection::forDirectories()
			->name('Livewire')
			->inOrEmpty($this->registry->getModulesPath().'/*/src')
			->withModuleInfo()
			->values()
			->map(fn(ModuleFileInfo $file) => [
				'prefix' => $file->module()->name,
				'namespace' => $file->module()->qualify('Livewire'),
				'viewFolder' => $file->module()->path('resources/views/livewire'),
			])->toArray();
	}

	/**
	 * @param Collection<int, array{module: string, name: string, fqcn: string}> $data
	 */
	public function handle(Collection $data): void
	{
		$data->each(function(array $row) {
			Livewire::addLocation($row['viewFolder']);
			Livewire::addNamespace($row['prefix'], $row['viewFolder']);
			Livewire::addLocation(classNamespace: $row['namespace']);
			Livewire::addNamespace(
				namespace: $row['prefix'],
				classNamespace: $row['namespace'],
				classViewPath: $row['viewFolder']
			);
		});
	}
}
