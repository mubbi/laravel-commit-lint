<?php

namespace Mubbi\CommitLint\Tests;

use Illuminate\Filesystem\Filesystem;
use Orchestra\Testbench\TestCase;
use Mubbi\CommitLint\CommitLintServiceProvider;

class InstallHookCommandTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [CommitLintServiceProvider::class];
    }

    protected function tearDown(): void
    {
        \Mockery::close();
        $this->app->bind(Filesystem::class, fn () => new Filesystem);
        parent::tearDown();
    }

    public function test_install_hook_command_creates_hook_file()
    {
        $fs = new Filesystem;
        $testHooksDir = base_path('tests/.git/hooks');
        $testHookPath = $testHooksDir . '/commit-msg';
        $stubPath = __DIR__ . '/../src/Hooks/commit-msg';
        $stubPathTest = __DIR__ . '/../src/Hooks/commit-msg.test';

        if (!file_exists($stubPath)) {
            $this->markTestSkipped('Stub file not found.');
        }

        // Use a temporary stub for this test
        $fs->copy($stubPath, $stubPathTest);

        $fs->ensureDirectoryExists($testHooksDir);
        if ($fs->exists($testHookPath)) {
            $fs->delete($testHookPath);
        }

        // Temporarily patch the command to use the test stub
        $this->artisan('commitlint:install', [
            'hookPath' => $testHookPath,
            'stubPath' => $stubPathTest
        ])->assertExitCode(0);

        $this->assertFileExists($testHookPath);
        $this->assertEquals(
            file_get_contents($stubPathTest),
            file_get_contents($testHookPath)
        );

        // Clean up test stub after test
        if ($fs->exists($stubPathTest)) {
            $fs->delete($stubPathTest);
        }
    }

    public function test_install_hook_command_hooks_dir_missing()
    {
        $fs = new Filesystem;
        $testGitMissingDir = base_path('tests/.git_missing');
        $testHooksDir = $testGitMissingDir . '/hooks';
        $testHookPath = $testHooksDir . '/commit-msg';

        if ($fs->exists($testGitMissingDir)) {
            $fs->deleteDirectory($testGitMissingDir);
        }

        $this->assertFalse($fs->exists($testHooksDir));

        $command = $this->artisan('commitlint:install', [
            'hookPath' => $testHookPath
        ])->assertExitCode(1);

    }

    public function test_install_hook_command_existing_hook_no_overwrite()
    {
        $fs = new Filesystem;
        $testHooksDir = base_path('tests/.git/hooks');
        $testHookPath = $testHooksDir . '/commit-msg';
        $stubPath = __DIR__ . '/../src/Hooks/commit-msg';
        $stubPathTest = __DIR__ . '/../src/Hooks/commit-msg.test';

        if (!file_exists($stubPath)) {
            $this->markTestSkipped('Stub file not found.');
        }

        $fs->copy($stubPath, $stubPathTest);

        $fs->ensureDirectoryExists($testHooksDir);
        $fs->put($testHookPath, 'existing hook');

        $this->artisan('commitlint:install', [
            'hookPath' => $testHookPath,
            'stubPath' => $stubPathTest
        ])
            ->expectsConfirmation('commit-msg hook already exists. Overwrite?', 'no')
            ->expectsOutput('Aborted. Existing hook not overwritten.')
            ->assertExitCode(0);

        // Clean up test stub after test
        if ($fs->exists($stubPathTest)) {
            $fs->delete($stubPathTest);
        }
    }

    public function test_install_hook_command_copy_fails()
    {
        $fs = new Filesystem;
        $testHooksDir = base_path('tests/.git/hooks');
        $testHookPath = $testHooksDir . '/commit-msg';
        $stubPath = __DIR__ . '/../src/Hooks/commit-msg';
        $stubPathTest = __DIR__ . '/../src/Hooks/commit-msg.test';

        if (!file_exists($stubPath)) {
            $this->markTestSkipped('Stub file not found.');
        }

        $fs->copy($stubPath, $stubPathTest);

        $fs->ensureDirectoryExists($testHooksDir);
        // Delete the test stub to simulate missing stub
        if ($fs->exists($stubPathTest)) {
            $fs->delete($stubPathTest);
        }

        $this->artisan('commitlint:install', [
            'hookPath' => $testHookPath,
            'stubPath' => $stubPathTest
        ])
        ->expectsOutput("Stub file not found at {$stubPathTest}")
        ->assertExitCode(1);

        // Restore the test stub for other tests
        // Clean up test stub after test
        if ($fs->exists($stubPathTest)) {
            $fs->delete($stubPathTest);
        }
    }

    public function test_install_hook_command_chmod_fails()
    {
        $mockFs = \Mockery::mock(Filesystem::class);
        // Simulate hook file exists, stub exists, copy succeeds, chmod fails
        $mockFs->shouldReceive('exists')->withArgs(function($path) {
            // Simulate hook file and stub both exist
            return true;
        })->andReturn(true);
        $mockFs->shouldReceive('copy')->andReturn(true);
        $mockFs->shouldReceive('chmod')->andReturn(false);

        $this->app->bind(Filesystem::class, fn () => $mockFs);

        $stubPathTest = __DIR__ . '/../src/Hooks/commit-msg.test';
        $this->artisan('commitlint:install', [
            'hookPath' => base_path('tests/.git/hooks/commit-msg'),
            'stubPath' => $stubPathTest
        ])
        ->expectsConfirmation('commit-msg hook already exists. Overwrite?', 'yes')
        ->expectsOutput('Failed to set hook file as executable.')
        ->assertExitCode(1);
        // Clean up test stub after test
        $fsCleanup = new Filesystem;
        if ($fsCleanup->exists($stubPathTest)) {
            $fsCleanup->delete($stubPathTest);
        }
    }
}
