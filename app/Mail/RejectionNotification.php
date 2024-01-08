<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RejectionNotification extends Mailable
{
    use Queueable, SerializesModels;

    private $data=[];
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data=$data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('muhammad@example.com','Muhammad Alzabibi')
                    ->subject($this->data['subject'])
                    ->view('emails.rejection');



                    // return $this->subject('Rejection Notification')
        //             ->view('emails.rejection')
        //             ->with([
        //                 'body' => 'Your request to be a seller in car auto parts has been rejected.'
        //             ]);
    }

    }

