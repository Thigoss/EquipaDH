<?php

namespace App\Mail;

use App\Models\Token;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UsuarioReprovado extends Mailable {

    use Queueable,
        SerializesModels;
 
    private $user;

    private $motivo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $motivo) {
        $this->user = $user;
        $this->motivo = $motivo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {

        return $this->subject('[SIPIA Conselho Tutelar MDHC] - Reprovação de Cadastro')
                        ->view('mail.usuario_reprovado')
                        ->with([
                            'user' => $this->user,
                            'motivo' => $this->motivo
        ]);
    }

}
