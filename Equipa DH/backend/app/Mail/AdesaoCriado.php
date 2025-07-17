<?php

namespace App\Mail;

use App\Models\Token;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdesaoCriado extends Mailable {

    use Queueable,
        SerializesModels;
 
    private $user;
    private $adesao;

    public function __construct($adesao, $user) {
        $this->adesao = $adesao;
        $this->user = $user;
    }

    public function build() {

        return $this->subject('[EQUIPADH] - Solicitação enviada')
                        ->view('mail.adesao_criado')
                        ->with([
                            'adesao' => $this->adesao,
                            'user' => $this->user
        ]);
    }

}
