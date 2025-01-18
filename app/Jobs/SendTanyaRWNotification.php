<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\TanyaRWNotification;

class SendTanyaRWNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $user;
    public $text;
    public $name;
    /**
     * Create a new job instance.
     */
    public function __construct($user, $text, $name)
    {
        $this->user = $user;
        $this->text = $text;
        $this->name = $name;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->user->notify(new TanyaRWNotification([
            'judul'     => $this->name,
            'deskripsi' => $this->text,
        ]));
    }
}
