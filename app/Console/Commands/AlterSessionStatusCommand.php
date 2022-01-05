<?php

namespace App\Console\Commands;

use App\Models\Client\Session;
use Illuminate\Console\Command;

class AlterSessionStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alter:session_status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to alter client session status to meet new requirements.';

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
        $sessions = Session::where('user_id', null)->get();

        foreach ($sessions as $session) {
            $session->update(['status' => 0]);
        }
    }
}
