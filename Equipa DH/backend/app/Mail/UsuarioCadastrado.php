<?php

namespace App\Mail;

use App\Models\Token;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UsuarioCadastrado extends Mailable {

    use Queueable,
        SerializesModels;
 
    private $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user) {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {

        return $this->subject('[SIPIA Conselho Tutelar MDHC] - Aprovação de Solicitação')
                        ->view('mail.usuario_cadastrado')
                        ->with([
                            'user' => $this->user
        ]);
    }

}
