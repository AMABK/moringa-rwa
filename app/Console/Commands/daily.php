<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;

class daily extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs daily module progress update messages';

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
        // create new instance of call the notification class
        $notification = new \App\Http\Controllers\NotificationController();
        //call the checkStudentModuleStatus() method
        $notification->checkStudentModuleStatus();
        $this->info('Daily update run successfully');
    }
}
