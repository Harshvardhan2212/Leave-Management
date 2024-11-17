<?php

namespace App\Jobs;

use App\Mail\PasswordResetMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;

class ResetMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    protected $password;

    /**
     * Create a new job instance.
     */
    public function __construct($user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Send the email
            // Mail::to($this->user->email)->send(new PasswordResetMail($this->user->first_name, $this->password));
            Mail::to($this->user->email)->send(new PasswordResetMail($this->user->first_name, $this->password));

            // Optionally log success
            Log::info("Password reset email sent successfully to {$this->user->email}");
        } catch (Exception $e) {
            // Handle the error, log it
            Log::error("Failed to send password reset email to {$this->user->email}: " . $e->getMessage());
            
            // Optionally, rethrow the exception if you want the job to be retried
            throw $e;
        }
    }
}
