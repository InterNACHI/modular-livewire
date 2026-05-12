<?php

namespace InterNACHI\ModularLivewire\Tests\Concerns;

use Illuminate\Filesystem\Filesystem;
use PHPUnit\Framework\Attributes\Before;

trait PreloadsAppModules
{
	protected static $autoloader_registered = false;
	
	#[Before]
	public function prepareTestModules(): void
	{
		$src = __DIR__.'/../testbench-core/app-modules';
		$dest = static::applicationBasePath().'/app-modules';
		
		$fs = new Filesystem();
		$fs->deleteDirectory($dest);
		$fs->copyDirectory($src, $dest);
	}
	
	#[Before]
	public function prepareModuleAutoloader(): void
	{
		if (! static::$autoloader_registered) {
			spl_autoload_register(function($fqcn) {
				$namespaces = [
					'TestModule\\' => 'test-module',
					'TestModuleTwo\\' => 'test-module-two',
				];
				
				foreach ($namespaces as $namespace => $module) {
					if (str_starts_with($fqcn, $namespace)) {
						$path = str_replace(
							[$namespace, '\\'],
							['', DIRECTORY_SEPARATOR],
							$fqcn
						);
						$path = static::applicationBasePath().'/app-modules/'.$module.'/src/'.$path.'.php';
						if (file_exists($path)) {
							include_once $path;
						}
						return;
					}
				}
			});
		}
		static::$autoloader_registered = true;
	}
}
