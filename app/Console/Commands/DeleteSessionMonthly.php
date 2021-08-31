<?php

namespace App\Console\Commands;

use App\Models\Client\Session;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteSessionMonthly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete-session-monthly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will delete the session permanently every month.';

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
        Session::whereDate('created_at', Carbon::now()->subMonth())->delete();
    }
}
