<?php

namespace App\Repository;

use App\Models\Cronograma;
use App\Models\PerfilUser;
use Illuminate\Database\Eloquent\Collection;
use DB;
use Illuminate\Support\Facades\Log;

/**
 * Classe de manipulação da entidade de cronogramas
 *
 * @author Eduardo Praxedes Heinske (eduardo.praxedes@jointecnologia.com.br)
 */
class CronogramaRepository extends BaseRepository
{

    /**
     * Model de cronograma
     *
     * @var Cronograma
     */
    protected $model;


    /**
     * Construtor
     *
     * @param Cronograma $model
     */
    public function __construct(Cronograma $model)
    {
        $this->model = $model;
    }

    /**
     * Realiza a pesquisa por cronogramas	
     *
     * @param string $search
     * @param array $filtros
     * @param boolean $limit
     * @return Collection
     */
    public function search(string $search = '', array $filtros = [], $limit = false)
    {
        $query = $this->model->with(['programa']);        

        // check de contexto para procurar apenas os cronogramas dos programas da unidade do user
        $contexto = ContextoRepository::contextoLogado();
        if ($contexto['contexto']->perfil->tipo != PerfilUser::TP_ADMIN) {
            $query = $query->whereHas('programa', function ($query) use ($contexto) {
                if ($contexto['contexto']['unidade']) {
                    $query->where('unidade_id', $contexto['contexto']['unidade_id']);
                }
            });
        }

        if ($search) {
            $query = $query->where('codigo', 'ilike', '%' . $search . '%');
        }
        
        if (isset($filtros['id']) && !empty($filtros['id'])) {
            $query = $query->where('id', $filtros['id']);
        }

        if (isset($filtros['numero']) && !empty($filtros['numero'])) {
            $query = $query->where('numero', $filtros['numero']);
        }

        if (isset($filtros['programa_id']) && !empty($filtros['programa_id'])) {
            $query = $query->where('programa_id', $filtros['programa_id']);

            $this->atualizarSituacaoLote($filtros['programa_id']);
        } else {
            $this->atualizarSituacaoLote();
        }
        
        if (isset($filtros['data_inicio']) && !empty($filtros['data_inicio'])) {
            $query = $query->where('data_publicacao_inicio', '>=' ,$filtros['data_inicio']);
        }
        
        if (isset($filtros['data_termino']) && !empty($filtros['data_termino'])) {
            $query = $query->where('data_divulgacao_lista', '<=' ,$filtros['data_termino']);
        }

        if (isset($filtros['fase']) && !empty($filtros['fase'])) {
            $query = $query->where('fase', $filtros['fase']);
        }

        if (isset($filtros['publicacao']) && !empty($filtros['publicacao'])) {
            $query = $query->where('publicacao', $filtros['publicacao']);
        }

        if (isset($filtros['situacao']) && !empty($filtros['situacao'])) {
            $query = $query->where('situacao', $filtros['situacao']);
        }

        $query = $query->orderBy('updated_at', 'desc');
        
        if ($limit !== false) {
            return $query->paginate($limit);
        } else {
            return $query->get();
        }
    }

    /**
     * Obtem os dados de um cronograma
     *
     * @param integer $id
     * @return Cronograma
     */
    public function find($id) : Cronograma
    {
        return $this->model->with(['programa', 'programa.unidade'])->where('id', $id)->first();
    }
    
    /**
     * Realiza o cadastro / alteração de dados de um cronograma
     *
     * @param array $data
     * @param boolean $sync
     * @return boolean
     */
    public function save(array $data, bool $sync = true) : bool
    {
        try {
            DB::beginTransaction();

            // Verifica a existência do id para criar ou alterar
            if (isset($data['id']) && $data['id']) {

                // Busca e altera os dados da solicitação
                $model = $this->model->find($data['id']);
                $model->fill($data)->save();
                
            } else {            
                $data['numero'] = $this->gerarNumero($data['programa_id']);
                $data['situacao'] = Cronograma::ST_NAO_INICIADO;
                $data['fase'] = Cronograma::FA_NAO_INICIADO;
                $data['publicacao'] = Cronograma::PU_PUBLICADO;

                // Insere a solicitação
                $this->model->fill($data)->save();
                $model = $this->model;
            }

            DB::commit();
            return true;
        } catch (\Exception $ex) {
            report($ex);
            return false;
        }
    }

    public function gerarNumero($programa)
    {
        // gerar no padrão NUMERO/ANO
        $ano = date('Y');
        $numero = $this->model->whereYear('created_at', $ano)->where('programa_id', $programa)->count() + 1;
        return $numero . '/' . $ano;
    }

    /**
     * Atualiza a situação de um lote de cronogramas
     * 
     * @return void
     */
    public function atualizarSituacaoLote($programa = null)
    {
        if ($programa) {
            $cronogramas = $this->model->where('programa_id', $programa)
                ->where('situacao', '!=', Cronograma::ST_CANCELADO)
                ->where('situacao', '!=', Cronograma::ST_FINALIZADO)
                ->get();
        } else {
            $cronogramas = $this->model->all();
        }

        foreach($cronogramas as $cronograma){
            $this->alterarStatus($cronograma->id);
            $this->alterarFase($cronograma->id);
        }
    }

    /**
     * Altera o status de um cronograma de acordo com as datas de faseamento
     * 
     * @param integer $id
     * @return boolean
     */
    public function alterarStatus(int $id) : bool
    {

        $cronograma = $this->model->find($id);

        if($cronograma->situacao === Cronograma::ST_CANCELADO){
            return false;
        }
        
        $currentDate = date('Ymd');

        $inicio = $cronograma->data_adesao_inicio ? str_replace('-', '', $cronograma->data_adesao_inicio) : null;
        $encerramento = $cronograma->data_divulgacao_lista ? str_replace('-', '', $cronograma->data_divulgacao_lista) : null;
        
        if ($inicio && $encerramento) {
            if ($inicio > $currentDate && $encerramento > $currentDate) {
                $cronograma->situacao = Cronograma::ST_NAO_INICIADO;
            } elseif ($inicio <= $currentDate && $encerramento >= $currentDate) {
                $cronograma->situacao = Cronograma::ST_EM_ANDAMENTO;
            } elseif ($encerramento < $currentDate) {
                $cronograma->situacao = Cronograma::ST_FINALIZADO;
            }
        }

        return $cronograma->save();
    }

    /**
     * Altera a fase de um cronograma de acordo com as datas de faseamento
     * 
     * @param integer $id
     * @return boolean
     */
    public function alterarFase(int $id) : bool
    {
        $cronograma = $this->model->find($id);

        if($cronograma->situacao === Cronograma::ST_CANCELADO){
            return false;
        }

        $data_publicacao_inicio = $cronograma->data_publicacao_inicio ? $cronograma->data_publicacao_inicio : null;
        $data_publicacao_inicio = str_replace('-', '', $data_publicacao_inicio);

        $data_adesao_inicio = $cronograma->data_adesao_inicio ? $cronograma->data_adesao_inicio : null;
        $data_adesao_inicio = str_replace('-', '', $data_adesao_inicio);

        $data_adesao_fim = $cronograma->data_adesao_fim ? $cronograma->data_adesao_fim : null;
        $data_adesao_fim = str_replace('-', '', $data_adesao_fim);

        $data_divulgacao_adesao_inicio = $cronograma->data_divulgacao_adesao_inicio ? $cronograma->data_divulgacao_adesao_inicio : null;
        $data_divulgacao_adesao_inicio = str_replace('-', '', $data_divulgacao_adesao_inicio);

        $data_recurso_adesao_inicio = $cronograma->data_recurso_adesao_inicio ? $cronograma->data_recurso_adesao_inicio : null;
        $data_recurso_adesao_inicio = str_replace('-', '', $data_recurso_adesao_inicio);

        $data_recurso_adesao_fim = $cronograma->data_recurso_adesao_fim ? $cronograma->data_recurso_adesao_fim : null;
        $data_recurso_adesao_fim = str_replace('-', '', $data_recurso_adesao_fim);

        $data_divulgacao_habilitados_inicio = $cronograma->data_divulgacao_habilitados_inicio ? $cronograma->data_divulgacao_habilitados_inicio : null;
        $data_divulgacao_habilitados_inicio = str_replace('-', '', $data_divulgacao_habilitados_inicio);

        $data_recurso_habilitados_inicio = $cronograma->data_recurso_habilitados_inicio ? $cronograma->data_recurso_habilitados_inicio : null;
        $data_recurso_habilitados_inicio = str_replace('-', '', $data_recurso_habilitados_inicio);

        $data_recurso_habilitados_fim = $cronograma->data_recurso_habilitados_fim ? $cronograma->data_recurso_habilitados_fim : null;
        $data_recurso_habilitados_fim = str_replace('-', '', $data_recurso_habilitados_fim);

        $data_divulgacao_lista = $cronograma->data_divulgacao_lista ? $cronograma->data_divulgacao_lista : null;
        $data_divulgacao_lista = str_replace('-', '', $data_divulgacao_lista);

        $fase = $cronograma->fase;

        if (!empty($data_publicacao_inicio) && $data_publicacao_inicio > date('Ymd')) {
            $fase = Cronograma::FA_NAO_INICIADO;
        }
        
        if (!empty($data_publicacao_inicio) && $data_publicacao_inicio <= date('Ymd')) {
            $fase = Cronograma::FA_PUBLICACAO;
        }
        
        if (!empty($data_adesao_inicio) && $data_adesao_inicio <= date('Ymd') && $data_adesao_fim >= date('Ymd')){
            $fase = Cronograma::FA_ADESAO;
        }

        if(!empty($data_adesao_fim) && $data_adesao_fim < date('Ymd')){
            $fase = Cronograma::FA_FIM_ADESAO;
        }
        
        if(!empty($data_divulgacao_adesao_inicio) && $data_divulgacao_adesao_inicio <= date('Ymd')){
            $fase = Cronograma::FA_DIVULGACAO_ADESAO;
        }
        
        if(!empty($data_recurso_adesao_inicio) && $data_recurso_adesao_inicio <= date('Ymd') && $data_recurso_adesao_fim >= date('Ymd')){
            $fase = Cronograma::FA_RECURSO_ADESAO;
        }

        if(!empty($data_recurso_adesao_fim) && $data_recurso_adesao_fim < date('Ymd')){
            $fase = Cronograma::FA_FIM_RECURSO_ADESAO;
        }
        
        if(!empty($data_divulgacao_habilitados_inicio) && $data_divulgacao_habilitados_inicio <= date('Ymd')){
            $fase = Cronograma::FA_DIVULGACAO_HABILITADOS;
        }
        
        if(!empty($data_recurso_habilitados_inicio) && $data_recurso_habilitados_inicio <= date('Ymd') && $data_recurso_habilitados_fim >= date('Ymd')){
            $fase = Cronograma::FA_RECURSO_HABILITADOS;
        }

        if(!empty($data_recurso_habilitados_fim) && $data_recurso_habilitados_fim < date('Ymd')){
            $fase = Cronograma::FA_FIM_RECURSO_HABILITADOS;
        }
        
        if(!empty($data_divulgacao_lista) && $data_divulgacao_lista <= date('Ymd')){
            $fase = Cronograma::FA_DIVULGACAO_LISTA;
        }

        $cronograma->fase = $fase;
        return $cronograma->save();
    }

    /**
     * Cancela um cronograma
     * 
     * @param array $dados
     * @return boolean
     */
    public function cancelar(array $dados = []) : bool
    {
        try {
            DB::beginTransaction();

            $cronograma = $this->model->find($dados['id']);
            $cronograma->situacao = Cronograma::ST_CANCELADO;
            $cronograma->arquivo_cancelamento = $dados['arquivo_cancelamento'];
            $cronograma->justificativa_cancelamento = $dados['justificativa_cancelamento'];
            $cronograma->save();

            DB::commit();
            return true;
        } catch (\Exception $ex) {
            report($ex);
            DB::commit();
            return false;
        }
    }
 
    /**
     * Remove uma solicitação
     *
     * @param integer $id
     * @return boolean
     */
    public function delete($id) : bool
    {
        try {
            DB::beginTransaction();
            
            $user = $this->model->find($id);
            $user->delete();

            DB::commit();
            return true;
        } catch (\Exception $ex) {
            report($ex);
            DB::commit();
            return false;
        }
    }
}