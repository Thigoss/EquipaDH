<?php

namespace App\Mail;

use App\Models\Token;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UsuarioAtivado extends Mailable {

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

        return $this->subject('[SIPIA Conselho Tutelar MDHC] - Ativação de Cadastro')
                        ->view('mail.usuario_ativado')
                        ->with([
                            'user' => $this->user
        ]);
    }

}
