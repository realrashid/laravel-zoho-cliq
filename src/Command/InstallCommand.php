<?php

namespace RealRashid\Cliq\Command;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cliq:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Zoho Cliq integration by publishing the configuration file';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->isAlreadyInstalled()) {
            $this->error('Cliq is already installed.');
            return;
        }

        $this->info('Installing the Zoho Cliq...');
        $this->comment('Initiating the publication of Cliq Config...');

        $this->callSilent('vendor:publish', ['--tag' => 'cliq-config', '--force' => true]);

        $this->info('Laravel Zoho Cliq installed successfully.');
        $this->line('Thank you for using Zoho Cliq!');

        // Prompt the user to show support
        $response = $this->ask('Wanna show Zoho Cliq some love by starring it on GitHub? (yes/no) [no]:', 'no');

        if (strtolower($response) === 'yes') {
            $this->info('Opening GitHub repository page...');
            $this->openGitHubRepo();
        } else {
            $this->info('No worries! Thank you for using Zoho Cliq.');
        }
    }

    /**
     * Open the GitHub repository page.
     *
     * @return void
     */
    protected function openGitHubRepo()
    {
        $url = 'https://github.com/realrashid/laravel-zoho-cliq';
        if (PHP_OS_FAMILY === 'Windows') {
            pclose(popen("start $url", "r")); // Windows
        } elseif (PHP_OS_FAMILY === 'Darwin') {
            exec("open $url"); // macOS
        } else {
            exec("xdg-open $url"); // Linux
        }
    }

    /**
     * Check if the package is already installed.
     *
     * @return bool
     */
    protected function isAlreadyInstalled()
    {
        return File::exists(config_path('cliq.php'));
    }
}
