<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ActivateSystemOps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
{
    // Example: write to a config file, or update DB, or touch a flag
    file_put_contents(storage_path('app/ops_flag.txt'), '117X_ENABLED');
}

}
