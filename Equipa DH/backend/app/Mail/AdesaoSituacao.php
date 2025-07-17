<?php

namespace App\Mail;

use App\Models\Adesao;
use App\Models\Solicitacao;
use App\Models\Token;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdesaoSituacao extends Mailable {

    use Queueable,
        SerializesModels;
 
    private $user;
    private $adesao;
    private $observacao;
    private $status;
    private $anexo;

    public function __construct($adesao, $user, $observacao, $status, $anexo = null) {
        $this->adesao = $adesao;
        $this->user = $user;
        $this->observacao = $observacao;
        $this->status = $status;
        $this->anexo = $anexo;
    }

    public function build() {
        $view = '';

        switch ($this->status) {
            case Adesao::ST_APROVADO:
                $situacao = "aprovada";
                $view = 'mail.adesao_aprovado';
                break;
            case Adesao::ST_RECUSADO:
                $situacao = "reprovada";
                $view = 'mail.adesao_reprovado';
                break;
            case Adesao::ST_DEVOLVIDO:
                $situacao = "devolvida";
                $view = 'mail.adesao_devolvido';
                break;
            default:
                throw new \Exception("Status de solicitação inválido");
                break;
        }

        if ($this->anexo) {
            $this->attach(public_path($this->anexo));
        }

        $subject = "[EQUIPADH] - Solicitação $situacao";
        return $this->subject($subject)
                        ->view($view)
                        ->with([
                            'adesao' => $this->adesao,
                            'user' => $this->user,
                            'observacao' => $this->observacao,
                            'status' => $this->status,
                            'situacao' => $situacao,
                            'url' => env('APP_URL')
        ]);
    }

}
