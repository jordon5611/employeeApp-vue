<?php

namespace App\Jobs;

use App\Models\Employee;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;


class SendWelomeMail implements ShouldQueue
{
    use Queueable;

    //public $employee;

    /**
     * Create a new job instance.
     */
    public function __construct(public Employee $employee)
    {
        $this->employee = $employee;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        Mail::to($this->employee->email)->send(new WelcomeEmail($this->employee));
    }
}
