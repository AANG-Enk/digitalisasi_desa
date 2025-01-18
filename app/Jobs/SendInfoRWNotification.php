<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\InfoRWNotification;

class SendInfoRWNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $users;
    public $info;

    /**
     * Create a new job instance.
     */
    public function __construct($users, $info)
    {
        $this->users = $users;
        $this->info = $info;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach($this->users as $user){
            $user->notify(new InfoRWNotification($this->info));
        }
    }
}
