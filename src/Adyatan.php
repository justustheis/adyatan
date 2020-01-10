<?php

namespace JustusTheis\Adyatan;

use Artisan;
use Illuminate\Console\Command;

class Adyatan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update {--i|interactive}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the application via the git repository.';

    /**
     * Whether the application should be in maintenance mode during the update or not.
     *
     * @var bool
     */
    protected $shouldEnableMaintenanceMode;

    /**
     * Whether the maintenance mode should be disabled after the update or not.
     *
     * @var bool
     */
    protected $shouldDisableMaintenanceMode;

    /**
     * Whether the script should restart the supervisor or not.
     * This is only tested on Debian systems.
     *
     * @var bool
     */
    protected $shouldRestartSupervisor;

    /**
     * Whether the application caches should be cleared during the update or not.
     *
     * @var bool
     */
    protected $shouldClearCaches;

    /**
     * Whether the application caches should be rebuild after the update or not.
     *
     * @var bool
     */
    protected $shouldRebuildCaches;

    /**
     * Whether the script should pull from git or not.
     *
     * @var bool
     */
    protected $shouldPullFromGit;

    /**
     * Whether the composer dependencies should be updated during the update or not.
     *
     * @var bool
     */
    protected $shouldUpdateDependencies;

    /**
     * Whether the tables should be migrated during the update or not.
     *
     * @var bool
     */
    protected $shouldMigrateTables;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->checkEnvironment();
        $this->passwordProtection();
        if ($this->option('interactive')) {
            $this->questions();
        } else {
            $this->setOptions();
        }
        $this->enableMaintenance();
        $this->clearCaches();
        $this->pullFromGit();
        $this->updateDependencies();
        $this->migrateTables();
        $this->buildCaches();
        $this->restartSupervisor();
        $this->disableMaintenance();
        $this->line("");
        $this->info('Application updated!');
        $this->info('Thank you for using Adyatan!');
    }

    /**
     * Ask for user input in interactive mode to set options.
     *
     * @return void
     */
    protected function questions(): void
    {
        $this->shouldEnableMaintenanceMode = $this->confirm('Do you wish to put your application in maintenance mode?', true);
        $this->shouldDisableMaintenanceMode = $this->confirm('Do you wish to disable maintenance mode afterwards?', true);
        $this->shouldRestartSupervisor = $this->confirm('Do you wish to restart your supervisor?', true);
        $this->shouldClearCaches = $this->confirm('Do you wish to clear the application caches?', true);
        $this->shouldRebuildCaches = $this->confirm('Do you wish to rebuild the application caches afterwards?', true);
        $this->shouldPullFromGit = $this->confirm('Do you wish to pull from git?', true);
        $this->shouldUpdateDependencies = $this->confirm('Do you wish to update the application dependencies?', true);
        $this->shouldMigrateTables = $this->confirm('Do you wish to migrate your tables?', true);
    }

    /**
     * Set options via config file in non-interactive mode.
     *
     * @return void
     */
    protected function setOptions(): void
    {
        $this->shouldEnableMaintenanceMode = config('adyatan.options.shouldEnableMaintenanceMode');
        $this->shouldDisableMaintenanceMode = config('adyatan.options.shouldDisableMaintenanceMode');
        $this->shouldRestartSupervisor = config('adyatan.options.shouldRestartSupervisor');
        $this->shouldClearCaches = config('adyatan.options.shouldClearCaches');
        $this->shouldRebuildCaches = config('adyatan.options.shouldRebuildCaches');
        $this->shouldPullFromGit = config('adyatan.options.shouldPullFromGit');
        $this->shouldUpdateDependencies = config('adyatan.options.shouldUpdateDependecies');
        $this->shouldMigrateTables = config('adyatan.options.shouldMigrateTables');
    }

    /**
     * Check the current environment. If environment is production and
     * run_in_production is disabled, the script will exit here.
     *
     * @return void
     */
    protected function checkEnvironment(): void
    {
        if (\App::environment() == 'production' && ! config('adyatan.run_in_production')) {
            $this->error('Adyatan has been disabled for production. You can enable it in the adyatan config.');
			throw new \RuntimeException('Password incorrect.');
        }
    }

    /**
     * Check for password protection. If set ask for the corresponding
     * password and compare it to the configs password.
     *
     * @return void
     */
    protected function passwordProtection(): void
    {
        if ( ! config('adyatan.password')) {
            return;
        }

        $password = $this->secret('What is the password? (Your Input will not show. This is normal.)');

        if ($password == config('adyatan.password')) {
            return;
		}

		$this->error('Password incorrect.');
		throw new \RuntimeException('Password incorrect.');
    }

    /**
     * Enable maintenance mode if script is told to do so.
     *
     * @return void
     */
    protected function enableMaintenance(): void
    {
        if ($this->shouldEnableMaintenanceMode) {
            $this->warn('Put application in maintenance mode.');
            Artisan::call('down');
            $this->info('Application is in maintenance mode now.');
        }
    }

    /**
     * Disable maintenance mode if script is told to do so.
     *
     * @return void
     */
    protected function disableMaintenance(): void
    {
        if ($this->shouldDisableMaintenanceMode) {
            $this->warn('Disabling maintenance mode.');
            Artisan::call('up');
            $this->info('Application is live now.');
        }
    }

    /**
     * Clear application caches if script is told to do so.
     *
     * @return void
     */
    protected function clearCaches(): void
    {
        if ( ! $this->shouldClearCaches) {
            return;
        }
        $this->warn('Clearing Caches');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        $this->info('Caches Cleared');
    }

    /**
     * Rebuild application caches if script is told to do so.
     *
     * @return void
     */
    protected function buildCaches(): void
    {
        if ( ! $this->shouldRebuildCaches) {
            return;
        }
        $this->warn('Rebuilding Caches');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        $this->info('Caches Rebuild');
    }

    /**
     * Pull from git if script is told to do so.
     *
     * @return void
     */
    protected function pullFromGit(): void
    {
        if ( ! $this->shouldPullFromGit) {
            return;
        }
        $this->warn('Pulling from Git');
        shell_exec('git pull');
        $this->info('Pulled from Git');
    }

    /**
     * Update composer dependencies if script is told to do so.
     *
     * @return void
     */
    protected function updateDependencies(): void
    {
        if ( ! $this->shouldUpdateDependencies) {
            return;
        }
        $this->warn('Updating dependencies');
        shell_exec('composer install --optimize-autoloader --no-dev');
        $this->info('Dependencies updated');
    }

    /**
     * Migrate tables if script is told to do so.
     *
     * @return void
     */
    protected function migrateTables(): void
    {
        if ( ! $this->shouldMigrateTables) {
            return;
        }
        $this->warn('Migrating Tables');
        Artisan::call('migrate', ['--force' => true]);
        $this->info('Tables migrated');
    }

    /**
     * Restart supervisor if script is told to do so. This requires the user to
     * have sudo rights and possibly enter his password.
     * Only tested on Debian based distributions.
     *
     * @return void
     */
    protected function restartSupervisor(): void
    {
        if ( ! $this->shouldRestartSupervisor) {
            return;
        }
        shell_exec('sudo service supervisor restart');
    }
}
