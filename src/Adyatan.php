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

    protected $shouldEnableMaintenanceMode;

    protected $shouldDisableMaintenanceMode;

    protected $shouldRestartSupervisor;

    protected $shouldClearCaches;

    protected $shouldRebuildCaches;

    protected $shouldPullFromGit;

    protected $shouldUpdateDependencies;

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
		exit();
    }

    protected function questions()
    {
        $this->shouldEnableMaintenanceMode  = $this->confirm('Do you wish to put your application in maintenance mode?', true);
        $this->shouldDisableMaintenanceMode = $this->confirm('Do you wish to disable maintenance mode afterwards?', true);
        $this->shouldRestartSupervisor      = $this->confirm('Do you wish to restart your supervisor?', true);
        $this->shouldClearCaches            = $this->confirm('Do you wish to clear the application caches?', true);
        $this->shouldRebuildCaches          = $this->confirm('Do you wish to rebuild the application caches afterwards?', true);
        $this->shouldPullFromGit            = $this->confirm('Do you wish to pull from git?', true);
        $this->shouldUpdateDependencies     = $this->confirm('Do you wish to update the application dependencies?', true);
        $this->shouldMigrateTables          = $this->confirm('Do you wish to migrate your tables?', true);
    }

    protected function setOptions()
    {
        $this->shouldEnableMaintenanceMode  = config('adyatan.options.shouldEnableMaintenanceMode');
        $this->shouldDisableMaintenanceMode = config('adyatan.options.shouldDisableMaintenanceMode');
        $this->shouldRestartSupervisor      = config('adyatan.options.shouldRestartSupervisor');
        $this->shouldClearCaches            = config('adyatan.options.shouldClearCaches');
        $this->shouldRebuildCaches          = config('adyatan.options.shouldRebuildCaches');
        $this->shouldPullFromGit            = config('adyatan.options.shouldPullFromGit');
        $this->shouldUpdateDependencies     = config('adyatan.options.shouldUpdateDependecies');
        $this->shouldMigrateTables          = config('adyatan.options.shouldMigrateTables');
    }

    protected function checkEnvironment()
    {
        if (\App::environment() == 'production' && ! config('adyatan.run_in_production')) {
            $this->error('Adyatan has been disabled for production. You can enable it in the adyatan config.');
            exit();
        }
    }

    protected function passwordProtection()
    {
        if (! config('adyatan.password')) {
            return;
        }

        $password = $this->secret('What is the password? (Your Input will not show. This is normal.)');

        if ($password == config('adyatan.password')) {
            return;
        }

        $this->error('Password incorrect.');
        exit();
    }

    protected function enableMaintenance()
    {
        if ($this->shouldEnableMaintenanceMode) {
			$this->warn('Put application in maintenance mode.');
            Artisan::call('down');
            $this->info('Application is now in maintenance mode.');
        }
    }

    protected function disableMaintenance()
    {
        if ($this->shouldDisableMaintenanceMode) {
			$this->warn('Disabling maintenance mode.');
            Artisan::call('up');
            $this->info('Application is now live.');
        }
    }

    protected function clearCaches()
    {
        if (! $this->shouldClearCaches) {
            return;
        }
        $this->warn('Clearing Caches');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        $this->info('Caches Cleared');
    }

    protected function buildCaches()
    {
        if (! $this->shouldRebuildCaches) {
            return;
        }
        $this->warn('Rebuilding Caches');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        $this->info('Caches Rebuild');
    }

    protected function pullFromGit()
    {
        if (! $this->shouldPullFromGit) {
            return;
        }
        $this->warn('Pulling from Git');
        $return = shell_exec('git pull');
        $this->info('Pulled from Git');
    }

    protected function updateDependencies()
    {
		if (! $this->shouldUpdateDependencies) {
            return;
        }
        $this->warn('Updating dependencies');
        shell_exec('composer install --optimize-autoloader --no-dev');
        $this->info('Dependencies updated');
    }

    protected function migrateTables()
    {
		if (! $this->shouldMigrateTables) {
            return;
        }
        $this->warn('Migrating Tables');
        Artisan::call('migrate', ['--force' => true]);
        $this->info('Tables migrated');
    }

    protected function restartSupervisor()
	{
		if (! $this->shouldRestartSupervisor) {
            return;
        }
        shell_exec('sudo service supervisor restart');
    }
}
