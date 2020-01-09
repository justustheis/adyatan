<?php

return [
    'password'          => env('ADYATAN_PASSWORD', null),
    'run_in_production' => env('ADYATAN_RUN_IN_PRODUCTION', false),
    'options'           => [
        'shouldEnableMaintenanceMode'  => env('ADYATAN_ENABLE_MAINTENANCE_MODE', true),
        'shouldDisableMaintenanceMode' => env('ADYATAN_DISABLE_MAINTENANCE_MODE', true),
        'shouldRestartSupervisor'      => env('ADYATAN_RESTART_SUPERVISOR', true),
        'shouldClearCaches'            => env('ADYATAN_CLEAR_CACHES', true),
        'shouldRebuildCaches'          => env('ADYATAN_REBUILD_CACHES', true),
        'shouldPullFromGit'            => env('ADYATAN_PULL_FROM_GIT', true),
        'shouldUpdateDependencies'     => env('ADYATAN_UPDATE_DEPENDENCIES', true),
        'shouldMigrateTables'          => env('ADYATAN_MIGRATE_TABLES', true),
    ],
];
