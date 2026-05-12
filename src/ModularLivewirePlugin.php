<?php

namespace InterNACHI\ModularLivewire;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
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
		return FinderCollection::forFiles()
			->name('*.php')
			->inOrEmpty($this->registry->getModulesPath().'/*/src/Livewire')
			->withModuleInfo()
			->values()
			->map(fn(ModuleFileInfo $file) => [
				'module' => $file->module()->name,
				'name' => Str::of($file->getRelativePath())
					->explode('/')
					->filter()
					->push($file->getBasename('.php'))
					->map(fn(string $segment) => Str::kebab($segment))
					->implode('.'),
				'fqcn' => $file->fullyQualifiedClassName(),
			])
			->toArray();
	}
	
	/**
	 * @param Collection<int, array{module: string, name: string, fqcn: string}> $data
	 */
	public function handle(Collection $data): void
	{
		$data->each(fn(array $row) => Livewire::component(
			"{$row['module']}::{$row['name']}",
			$row['fqcn'],
		));
	}
}
