<?php

namespace App\Mail;

use App\Models\Item;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TradeCompleted extends Mailable
{
    use Queueable, SerializesModels;

    public $item;
    public $fromUser;

    public function __construct(Item $item, User $fromUser)
    {
        $this->item = $item;
        $this->fromUser = $fromUser;
    }

    public function build()
    {
        return $this->subject('取引が完了しました')
                    ->view('emails.trade_completed');
    }
}

