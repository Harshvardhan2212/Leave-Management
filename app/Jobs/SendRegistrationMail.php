<?php

namespace App\Jobs;

use App\Mail\EmployeeRegistered;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendRegistrationMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $password;
    /**
     * Create a new job instance.
     */
    public function __construct($user,$password)
    {
        $this->user = $user;
        $this->password = $password;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->user->email)->send(new EmployeeRegistered($this->user->first_name, $this->password));
    }
}
