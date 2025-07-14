<?php

namespace App\Jobs;

use App\Models\Form;
use App\Mail\FormCreatedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendFormCreatedNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $form;
    protected $recipientEmail;

    /**
     * Create a new job instance.
     */
    public function __construct(Form $form, string $recipientEmail)
    {
        $this->form = $form;
        $this->recipientEmail = $recipientEmail;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->recipientEmail)->send(new FormCreatedMail($this->form));
    }
}
