<div style="float: right;">
	<a href="https://github.com/InterNACHI/modular-livewire/actions" target="_blank">
		<img 
			src="https://github.com/InterNACHI/modular-livewire/workflows/PHPUnit/badge.svg" 
			alt="Build Status" 
		/>
	</a>
	<a href="https://packagist.org/packages/internachi/modular-livewire" target="_blank">
        <img 
            src="https://poser.pugx.org/internachi/modular-livewire/v/stable" 
            alt="Latest Stable Release" 
        />
	</a>
	<a href="./LICENSE" target="_blank">
        <img 
            src="https://poser.pugx.org/internachi/modular-livewire/license" 
            alt="MIT Licensed" 
        />
    </a>
</div>

# Modular Livewire

Livewire plugin for [`internachi/modular`](https://github.com/InterNACHI/modular). Automatically discovers and registers [Livewire](https://livewire.laravel.com/) components from your application modules.

## Requirements

- PHP 8.3+
- Laravel 11+
- [`internachi/modular`](https://github.com/InterNACHI/modular) ^3.0
- [`livewire/livewire`](https://github.com/livewire/livewire) ^3.0

## Installation

```bash
composer require internachi/modular-livewire
```

That's it. The package auto-registers its service provider and plugin via Laravel's package discovery.

## How It Works

This package registers a plugin with `internachi/modular`'s plugin architecture that automatically discovers Livewire components inside your modules. It scans for PHP files in each module's `src/Livewire/` directory and registers them with Livewire.

### Component Discovery

Place your Livewire components in `src/Livewire/` within any module:

```
app-modules/
├── billing/
│   └── src/
│       └── Livewire/
│           ├── InvoiceTable.php        → billing::invoice-table
│           └── Reports/
│               └── MonthlySummary.php  → billing::reports.monthly-summary
└── users/
    └── src/
        └── Livewire/
            └── UserProfile.php         → users::user-profile
```

### Naming Convention

Components are registered with the format `{module-name}::{component-name}`:

- Class names are converted to **kebab-case**
- Subdirectories use **dot notation**
- The module name prefix comes from the module's directory name

### Usage in Blade

```blade
<livewire:billing::invoice-table />

<livewire:billing::reports.monthly-summary />

<livewire:users::user-profile />
```
