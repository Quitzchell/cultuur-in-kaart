<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class RunLocalCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:local';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run local development server';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        chdir(base_path());

        $host = '0.0.0.0';
        $port = 8000;

        $this->line("<info>Laravel development server started:</info> http://{$host}:{$port}");

        $executableFinder = new PhpExecutableFinder();
        $phpExecutable = $executableFinder->find();
        $process = new Process([$phpExecutable, 'artisan', 'serve', '--host='.$host, '--port='.$port, '--tries=1']);
        $process->setTimeout(null)->run(function ($type, $line) {
            $this->output->write($line);
        });

        exit();
    }
}
