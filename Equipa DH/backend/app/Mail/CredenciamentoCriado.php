<?php

namespace App\Mail;

use App\Models\Token;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CredenciamentoCriado extends Mailable {

    use Queueable,
        SerializesModels;
 
    private $solicitacao;

    public function __construct($solicitacao) {
        $this->solicitacao = $solicitacao;
    }

    public function build() {

        return $this->subject('[EQUIPADH] - Solicitação de Credenciamento enviada')
                        ->view('mail.credenciamento_criado')
                        ->with([
                            'solicitacao' => $this->solicitacao
        ]);
    }

}
