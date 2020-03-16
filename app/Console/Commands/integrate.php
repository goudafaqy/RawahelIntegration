<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


class integrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:integrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Call Rawahel Implemetion';

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
     * @return mixed
     */
    public function handle()
    {

        $rawahel = new \App\Http\Controllers\rawahelController();
        $rawahel->index();
        //
    }
}
