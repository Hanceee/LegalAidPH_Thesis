<?php

namespace App\Observers;

use App\Models\Chat;
use Filament\Notifications\Notification;

class ChatObserver
{

    /**
     * Handle the Chat "deleted" event.
     */
    public function deleted(Chat $chat): void
    {

        Notification::make()
        ->title('Chat Deleted Notification!')
        ->danger()
        ->body('Your chat "' . $chat->name . '" has been deleted by the admin due to the use of profanity language. Please refrain from using inappropriate language in the future. Repeat offenses may result in a ban')
        ->sendToDatabase($chat->user);
    }


}
