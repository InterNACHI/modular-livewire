<?php

namespace InterNACHI\ModularLivewire\Tests;

use InterNACHI\Modular\Support\FinderFactory;
use InterNACHI\ModularLivewire\ModularLivewirePlugin;
use InterNACHI\ModularLivewire\Tests\Concerns\PreloadsAppModules;
use Livewire\Mechanisms\ComponentRegistry;
use ReflectionProperty;

class ModularLivewirePluginTest extends TestCase
{
	use PreloadsAppModules;
	
	public function test_discovers_livewire_components_from_modules(): void
	{
		$plugin = $this->app->make(ModularLivewirePlugin::class);
		$data = collect($plugin->discover($this->app->make(FinderFactory::class)));
		
		$this->assertCount(3, $data);
		
		$testComponent = $data->firstWhere('fqcn', 'TestModule\\Livewire\\TestComponent');
		$this->assertNotNull($testComponent);
		$this->assertEquals('test-module', $testComponent['module']);
		$this->assertEquals('test-component', $testComponent['name']);
		
		$nestedComponent = $data->firstWhere('fqcn', 'TestModule\\Livewire\\SubDir\\NestedComponent');
		$this->assertNotNull($nestedComponent);
		$this->assertEquals('test-module', $nestedComponent['module']);
		
		$anotherComponent = $data->firstWhere('fqcn', 'TestModuleTwo\\Livewire\\AnotherComponent');
		$this->assertNotNull($anotherComponent);
		$this->assertEquals('test-module-two', $anotherComponent['module']);
		$this->assertEquals('another-component', $anotherComponent['name']);
	}
	
	public function test_discovers_nested_livewire_components_with_dot_notation_names(): void
	{
		$plugin = $this->app->make(ModularLivewirePlugin::class);
		$data = collect($plugin->discover($this->app->make(FinderFactory::class)));
		
		$component = $data->firstWhere('fqcn', 'TestModule\\Livewire\\SubDir\\NestedComponent');
		$this->assertNotNull($component);
		$this->assertEquals('test-module', $component['module']);
		$this->assertEquals('sub-dir.nested-component', $component['name']);
	}
	
	public function test_registers_discovered_components_with_livewire(): void
	{
		$plugin = $this->app->make(ModularLivewirePlugin::class);
		$data = collect($plugin->discover($this->app->make(FinderFactory::class)));
		$plugin->handle($data);
		
		$registry = $this->app->make(ComponentRegistry::class);
		$aliases = (new ReflectionProperty($registry, 'aliases'))->getValue($registry);
		
		$this->assertEquals('TestModule\\Livewire\\TestComponent', $aliases['test-module::test-component']);
		$this->assertEquals('TestModule\\Livewire\\SubDir\\NestedComponent', $aliases['test-module::sub-dir.nested-component']);
		$this->assertEquals('TestModuleTwo\\Livewire\\AnotherComponent', $aliases['test-module-two::another-component']);
	}
	
	public function test_only_boots_when_livewire_is_installed(): void
	{
		$called = false;
		$handler = function() use (&$called) {
			$called = true;
		};
		
		ModularLivewirePlugin::boot($handler, $this->app);
		
		$this->assertTrue($called);
	}
	
	public function test_modules_without_livewire_directory_are_ignored(): void
	{
		$plugin = $this->app->make(ModularLivewirePlugin::class);
		$data = collect($plugin->discover($this->app->make(FinderFactory::class)));
		
		$this->assertTrue($data->where('module', 'test-module-no-livewire')->isEmpty());
	}
}
