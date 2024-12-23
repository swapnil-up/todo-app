<?php

namespace App\Listeners;

use App\Events\TodoCreated;
use App\Models\Todo;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendSummaryTodoEmail implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TodoCreated $event): void
    {
        $todoCount = Todo::count();
        if ($todoCount % 7 === 0) {
            $todos = Todo::latest()->take(7)->get();

            Mail::send('emails.todoSummary', ['todos' => $todos], function ($message) {
                $message->to('swapadhyay@gmail.com')
                    ->subject("Congrats, you've made 7 todos");
            });
        }
    }
}
