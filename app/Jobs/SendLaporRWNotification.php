<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\LaporRWNotification;

class SendLaporRWNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $info;

    /**
     * Create a new job instance.
     */
    public function __construct($user, $info)
    {
        $this->user = $user;
        $this->info = $info;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->user->notify(new LaporRWNotification($this->info));
    }
}
