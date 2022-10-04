<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\MonthlyReport;

class SendMonthlyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monthlyReport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = User::where('id', 2)->first();
        Mail::send('email.monthlyReport', function($message) {
            $message->subject('Monthly Report');
            $message->to($user->email);
            $message->line('Testing');
        });
        $this->info('Mail has fired.');
    }
}
