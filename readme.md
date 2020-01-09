# Adyatan

Adyatan updates laravel applications via git. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require justustheis/adyatan
```

## Configuration
**You can publish the config file using the artisan command:**
```bash
$ php artisan vendor:publish --provider="JustusTheis\Adyatan\AdyatanServiceProvider"
```

## Usage
**with interactive mode:**
```bash
$ php artisan update --interactive
$ php artisan update -i
```
**without interaction:**
```bash
$ php artisan update
```

## Environment Options (default)
```php
ADYATAN_PASSWORD = null
ADYATAN_RUN_IN_PRODUCTION = false
ADYATAN_ENABLE_MAINTENANCE_MODE = true
ADYATAN_DISABLE_MAINTENANCE_MODE = true
ADYATAN_RESTART_SUPERVISOR = true
ADYATAN_CLEAR_CACHES = true
ADYATAN_REBUILD_CACHES = true
ADYATAN_PULL_FROM_GIT = true
ADYATAN_UPDATE_DEPENDENCIES = true
ADYATAN_MIGRATE_TABLES = true
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## Credits

- [Justus Theis][link-author]
- Neha Sharma

## License

MIT License. Please see the [license file](license.md) for more information.

[link-author]: https://github.com/justustheis
