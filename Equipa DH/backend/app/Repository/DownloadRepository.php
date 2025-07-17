<?php 

namespace App\Repository;

use App\Models\Adesao;
use App\Models\AdesaoHistorico;
use App\Models\AdesaoRecursoArquivo;
use App\Models\ConfiguracaoGeral;
use App\Models\CronogramaArquivoClassificacaoEstadual;
use App\Models\CronogramaArquivoClassificacaoMunicipal;
use App\Models\Instituicao;
use App\Models\ProgramaHistorico;
use App\Models\Solicitacao;
use App\Models\SolicitacaoHistorico;
use App\User;
use Illuminate\Support\Facades\Log;

/**
 * Class DownloadRepository
 * 
 * @package App\Repository
 */
class DownloadRepository
{
    const TP_PROGRAMA_HISTORICO = 'programa_historico';
    const TP_CRON_ARQ_CLASS_ESTADUAL = 'arquivo_classificacao_estadual';
    const TP_CRON_ARQ_CLASS_MUNICIPAL = 'arquivo_classificacao_municipal';
    const TP_ANEXO_HIST_SOLICITACAO = 'anexo_historico_solicitacao';
    const TP_ANEXO_HIST_ADESOES = 'anexo_historico_adesoes';
    const TP_DOCUMENTO_HIST_ADESOES = 'documento_historico_adesoes';
    const TP_CONFIG_ARQ_ATO_DECLARACAO = 'config_arquivo_ato_declara';
    const TP_CONFIG_ARQ_HABILITACAO = 'config_arquivo_habilitacao';
    const TP_CONFIG_ARQ_ATO_DELEGACAO = 'config_arquivo_ato_delegacao';
    const TP_FORMULARIO_HIST_ADESOES = 'formulario_historico_adesoes';
    const TP_ADESAO_FORMULARIO_HABILITACAO = 'adesao_formulario_habilitacao';
    const TP_ADESAO_DECLARACAO = 'adesao_declaracao';
    const TP_ADESAO_TERMO_CONVOCACAO = 'adesao_termo_convocacao';
    const TP_ADESAO_RECURSO_ARQUIVO = 'adesao_recurso_arquivo';
    const TP_USER_RG = 'user_rg';
    const TP_USER_CPF = 'user_cpf';
    const TP_USER_ATO_POSSE = 'user_ato_posse';
    const TP_USER_ATO_DELEGACAO = 'user_ato_delegacao';
    const TP_INSTITUICAO_AUTO_RG = 'instituicao_auto_rg';
    const TP_INSTITUICAO_AUTO_CPF = 'instituicao_auto_cpf';
    const TP_INSTITUICAO_AUTO_ATO_POSSE = 'instituicao_auto_ato_posse';
    const TP_INSTITUICAO_AUTO_ATO_DELEGACAO = 'instituicao_auto_ato_delegacao';
    const TP_SOLICITACAO_RG = 'solicitacao_rg';
    const TP_SOLICITACAO_CPF = 'solicitacao_cpf';
    const TP_SOLICITACAO_ATO_POSSE = 'solicitacao_ato_posse';
    const TP_SOLICITACAO_ATO_DELEGACAO = 'solicitacao_ato_delegacao';
    const TP_SOLICITACAO_AUTO_RG = 'solicitacao_auto_rg';
    const TP_SOLICITACAO_AUTO_CPF = 'solicitacao_auto_cpf';
    const TP_SOLICITACAO_AUTO_ATO_POSSE = 'solicitacao_auto_ato_posse';
    const TP_SOLICITACAO_AUTO_ATO_DELEGACAO = 'solicitacao_auto_ato_delegacao';

    /**
     * Download
     * 
     * @param string $origin
     * @param int $id
     */
    public function download(string $origin, int $id)
    {
        $file = null;
        $fileName = null;
        
        switch ($origin) {
            case self::TP_PROGRAMA_HISTORICO:
                $historico = ProgramaHistorico::find($id);
                $file = $historico->logomarca;
                break;
            case self::TP_CRON_ARQ_CLASS_ESTADUAL:
                $arquivo = CronogramaArquivoClassificacaoEstadual::find($id);
                $file = $arquivo->arquivo;
                $fileName = $arquivo->nome;
                break;
            case self::TP_CRON_ARQ_CLASS_MUNICIPAL:
                $arquivo = CronogramaArquivoClassificacaoMunicipal::find($id);
                $file = $arquivo->arquivo;
                $fileName = $arquivo->nome;
                break;
            case self::TP_ANEXO_HIST_SOLICITACAO:
                $historico = SolicitacaoHistorico::find($id);
                $file = $historico->anexo;
                break;
            case self::TP_ANEXO_HIST_ADESOES:
                $historico = AdesaoHistorico::find($id);
                $file = $historico->anexo;
                break;
            case self::TP_DOCUMENTO_HIST_ADESOES:
                $historico = AdesaoHistorico::find($id);
                $file = $historico->arquivo;
                break;
            case self::TP_CONFIG_ARQ_HABILITACAO:
                $config = ConfiguracaoGeral::find($id);
                $file = $config->arquivo_habilitacao;
                break;
            case self::TP_CONFIG_ARQ_ATO_DECLARACAO:
                $config = ConfiguracaoGeral::find($id);
                $file = $config->arquivo_declaracao_unificada;
                break;
            case self::TP_CONFIG_ARQ_ATO_DELEGACAO:
                $config = ConfiguracaoGeral::find($id);
                $file = $config->arquivo_ato_delegacao;
                break;
            case self::TP_FORMULARIO_HIST_ADESOES:
                $historico = AdesaoHistorico::find($id);
                $file = $historico->formulario_habilitacao;
                break;
            case self::TP_ADESAO_FORMULARIO_HABILITACAO:
                $adesao = Adesao::find($id);
                $file = $adesao->formulario_habilitacao;
                break;
            case self::TP_ADESAO_DECLARACAO:
                $adesao = Adesao::find($id);
                $file = $adesao->arquivo;
                break;
            case self::TP_ADESAO_TERMO_CONVOCACAO:
                $adesao = Adesao::find($id);
                $file = $adesao->termo_convocacao;
                break;
            case self::TP_ADESAO_RECURSO_ARQUIVO:
                $arArquivo = AdesaoRecursoArquivo::find($id);
                $file = $arArquivo->arquivo;
                break;
            case self::TP_SOLICITACAO_RG:
                $solicitacao = Solicitacao::find($id);
                $file = $solicitacao->documento_rg;
                break;
            case self::TP_SOLICITACAO_CPF:
                $solicitacao = Solicitacao::find($id);
                $file = $solicitacao->documento_cpf;
                break;
            case self::TP_SOLICITACAO_ATO_POSSE:
                $solicitacao = Solicitacao::find($id);
                $file = $solicitacao->documento_ato_posse;
                break;
            case self::TP_SOLICITACAO_ATO_DELEGACAO:
                $solicitacao = Solicitacao::find($id);
                $file = $solicitacao->documento_ato_delegacao;
                break;
            case self::TP_SOLICITACAO_AUTO_RG:
                $solicitacao = Solicitacao::find($id);
                $file = $solicitacao->autoridade_rg;
                break;
            case self::TP_SOLICITACAO_AUTO_CPF:
                $solicitacao = Solicitacao::find($id);
                $file = $solicitacao->autoridade_cpf;
                break;
            case self::TP_SOLICITACAO_AUTO_ATO_POSSE:
                $solicitacao = Solicitacao::find($id);
                $file = $solicitacao->autoridade_ato_posse;
                break;
            case self::TP_SOLICITACAO_AUTO_ATO_DELEGACAO:
                $solicitacao = Solicitacao::find($id);
                $file = $solicitacao->autoridade_ato_delegacao;
                break;
            case self::TP_USER_RG:
                $user = User::find($id);
                $file = $user->documento_rg;
                break;
            case self::TP_USER_CPF:
                $user = User::find($id);
                $file = $user->documento_cpf;
                break;
            case self::TP_USER_ATO_POSSE:
                $user = User::find($id);
                $file = $user->documento_ato_posse;
                break;
            case self::TP_USER_ATO_DELEGACAO:
                $user = User::find($id);
                $file = $user->documento_ato_delegacao;
                break;
            case self::TP_INSTITUICAO_AUTO_RG:
                $instituicao = Instituicao::find($id);
                $file = $instituicao->autoridade_rg;
                break;
            case self::TP_INSTITUICAO_AUTO_CPF:
                $instituicao = Instituicao::find($id);
                $file = $instituicao->autoridade_cpf;
                break;
            case self::TP_INSTITUICAO_AUTO_ATO_POSSE:
                $instituicao = Instituicao::find($id);
                $file = $instituicao->autoridade_ato_posse;
                break;
            case self::TP_INSTITUICAO_AUTO_ATO_DELEGACAO:
                $instituicao = Instituicao::find($id);
                $file = $instituicao->autoridade_ato_delegacao;
                break;
            default:
                throw new \Exception('Origem não encontrada!');
                break;
        }

        if (!$file) {
            throw new \Exception('Arquivo não encontrado!');
        }
        
        return response()->download($file, $fileName);
    }
}