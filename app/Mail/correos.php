<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class correos extends Mailable
{
    use Queueable, SerializesModels;
    public $datos;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($datos)
    {
        $this->datos = $datos;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->datos->filename != '') {
            return $this->from($this->datos->from, $this->datos->fromname)
                ->view($this->datos->view)
                ->text($this->datos->text)
                ->attachData($this->datos->archivo, $this->datos->filename)
                ->subject($this->datos->subject);
        } else {
            return $this->from($this->datos->from, $this->datos->fromname)
                ->view($this->datos->view)
                ->text($this->datos->text)
                ->subject($this->datos->subject);
        }
    }
}