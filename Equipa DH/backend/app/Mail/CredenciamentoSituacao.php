<?php

namespace App\Mail;

use App\Models\Solicitacao;
use App\Models\Token;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CredenciamentoSituacao extends Mailable {

    use Queueable,
        SerializesModels;
 
    private $solicitacao;
    private $observacao;
    private $status;
    private $anexo;

    public function __construct($solicitacao, $observacao, $status, $anexo = null) {
        $this->solicitacao = $solicitacao;
        $this->observacao = $observacao;
        $this->status = $status;
        $this->anexo = $anexo;
    }

    public function build() {
        $view = 'mail.credenciamento_situacao';

        switch ($this->status) {
            case Solicitacao::ST_APROVADO:
                $situacao = "aprovado";
                $view = 'mail.credenciamento_aprovado';
                break;
            case Solicitacao::ST_REPROVADO:
                $situacao = "reprovado";
                $view = 'mail.credenciamento_reprovado';
                break;
            case Solicitacao::ST_PENDENTE:
                $situacao = "pendente";
                break;
            case Solicitacao::ST_DEVOLVIDO:
                $situacao = "devolvido";
                $view = 'mail.credenciamento_devolvido';
                break;
            default:
                break;
        }

        if ($this->anexo) {
            $this->attach(public_path($this->anexo));
        }

        $subject = "[EQUIPADH] - Credenciamento $situacao";
        return $this->subject($subject)
                        ->view($view)
                        ->with([
                            'solicitacao' => $this->solicitacao,
                            'observacao' => $this->observacao,
                            'status' => $this->status,
                            'situacao' => $situacao,
                            'url' => env('APP_URL')
        ]);
    }

}
