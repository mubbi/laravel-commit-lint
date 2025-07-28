<?php

declare(strict_types=1);

namespace Mubbi\CommitLint;

use Illuminate\Support\ServiceProvider;
use Mubbi\CommitLint\Console\InstallHookCommand;

/**
 * Service provider for CommitLint.
 *
 * @package Mubbi\CommitLint
 */
class CommitLintServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        // Register bindings if needed in future
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/Hooks/commit-msg' => \base_path('.git/hooks/commit-msg'),
            ], 'commitlint-hook');

            $this->commands([
                InstallHookCommand::class,
            ]);
        }
    }
}
