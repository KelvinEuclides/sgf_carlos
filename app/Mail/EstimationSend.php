<?php

namespace App\Mail;

use App\Utility;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EstimationSend extends Mailable
{
    use Queueable, SerializesModels;

    public $estimation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($estimation)
    {
        $this->estimation = $estimation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if(\Auth::user()->type == 'super admin')
        {
            return $this->view('email.estimation_send')->with('estimation', $this->estimation)->subject('Ragarding to product/service estimation generator.');
        }
        else
        {
            return $this->from(Utility::getValByName('company_email'), Utility::getValByName('company_email_from_name'))->view('email.estimation_send')->with('estimation', $this->estimation)->subject('Ragarding to product/service estimation generator.');
        }


    }
}
