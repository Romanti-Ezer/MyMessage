<?php

namespace App\Console\Commands;

use App\Message;
use App\User;
use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Notifications\GeneralMessage;
use Notification;

class SendGeneralMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:generalmessages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a message to a recipient';

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
        $messages = Message::all();
        
        $messages->map(function($message) {
            $today = Carbon::now();
            
            $this->line($today->format('m/d/Y'));

            if(($message->submissionsNumber === 0 || $message->frequency == 'daily') &&
            $today->gte($message->startDate) &&
            $today->lte($message->expirationDate))
            {
                $this->line("Message id " . $message->id . " will be sent");
                
                $user = User::find($message->user_id);
                Notification::route('mail', $message->recipientEmail)->notify(new GeneralMessage($message, $user));
                
                $message->submissionsNumber = $message->submissionsNumber + 1;
                $message->save();
            }
            $this->line("===================================");
        });
    }
}
