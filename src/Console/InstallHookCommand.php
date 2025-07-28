<?php

declare(strict_types=1);

namespace Mubbi\CommitLint\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

/**
 * Command to install the commit-msg Git hook for Conventional Commits.
 */
class InstallHookCommand extends Command
{
    protected $signature = 'commitlint:install {hookPath? : Custom path to install the commit-msg hook} {stubPath? : Custom path to the stub file}';
    protected $description = 'Install commit-msg Git hook for Conventional Commits';

    protected Filesystem $files;

    public function __construct(Filesystem $files = null)
    {
        parent::__construct();
        $this->files = $files ?? new Filesystem;
    }

    public function handle(): int
    {
        $hookPath = $this->argument('hookPath') ?? base_path('.git/hooks/commit-msg');
        $stubPath = $this->argument('stubPath') ?? (__DIR__ . '/../Hooks/commit-msg');

        $hooksDir = \dirname($hookPath);
        if (!$this->files->exists($hooksDir)) {
            $this->error("No .git/hooks directory found at {$hooksDir}. Is this a Git repository?");
            return 1;
        }

        if (!$this->files->exists($stubPath)) {
            $this->error("Stub file not found at {$stubPath}");
            return 1;
        }

        if ($this->files->exists($hookPath)) {
            if (!$this->confirm('commit-msg hook already exists. Overwrite?', false)) {
                $this->info('Aborted. Existing hook not overwritten.');
                return 0;
            }
        }

        if (!$this->files->copy($stubPath, $hookPath)) {
            $this->error('Failed to copy hook file. Check permissions.');
            return 1;
        }

        if (!$this->setExecutable($hookPath)) {
            $this->error('Failed to set hook file as executable.');
            return 1;
        }

        $this->info('✅ commit-msg hook installed successfully. Make sure to follow semantic commits from now on for new commits.');
        return 0;
    }

    protected function setExecutable(string $path): bool
    {
        return $this->files->chmod($path, 0755);
    }
}
