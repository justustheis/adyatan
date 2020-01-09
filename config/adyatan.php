<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Adyatan Password
    |--------------------------------------------------------------------------
    |
    | This value is the password for Adyatan. If this value is set to null no password is set.
    | Should you set this value to anything else than null, the update command will ask
    | for password input before any action will be performed.
    |
    */
    'password'          => env('ADYATAN_PASSWORD', null),

    /*
    |--------------------------------------------------------------------------
    | Adyatan Production Protection
    |--------------------------------------------------------------------------
    |
    | This value determines whether the Adyatan will run in production. If this value is set
    | to false, Adyatan won't run in production. Should you set it to true Adyatan will run.
    | Use this option with caution.
    |
    */
    'run_in_production' => env('ADYATAN_RUN_IN_PRODUCTION', false),

    /*
    |--------------------------------------------------------------------------
    | Adyatan Runtime Options
    |--------------------------------------------------------------------------
    |
    | These options will be used when Adyatan is run without interactions.
    |
    */
    'options'           => [
        /*
         * Should Adyatan enable maintenance mode during the update?
         */
        'shouldEnableMaintenanceMode'  => env('ADYATAN_ENABLE_MAINTENANCE_MODE', true),

        /*
         * Should Adyatan disable maintenance mode after the update?
         */
        'shouldDisableMaintenanceMode' => env('ADYATAN_DISABLE_MAINTENANCE_MODE', true),

        /*
         * Should Adyatan restart the supervisor after the update? This requires sudo and a Debian based distribution.
         */
        'shouldRestartSupervisor'      => env('ADYATAN_RESTART_SUPERVISOR', true),

        /*
         * Should Adyatan clear the application caches during the update?
         */
        'shouldClearCaches'            => env('ADYATAN_CLEAR_CACHES', true),

        /*
         * Should Adyatan rebuild the application caches after the update?
         */
        'shouldRebuildCaches'          => env('ADYATAN_REBUILD_CACHES', true),

        /*
         * Should Adyatan pull from git?
         */
        'shouldPullFromGit'            => env('ADYATAN_PULL_FROM_GIT', true),

        /*
         * Should Adyatan update the composer dependencies?
         */
        'shouldUpdateDependencies'     => env('ADYATAN_UPDATE_DEPENDENCIES', true),

        /*
         * Should Adyatan migrate the tables?
         */
        'shouldMigrateTables'          => env('ADYATAN_MIGRATE_TABLES', true),
    ],
];
