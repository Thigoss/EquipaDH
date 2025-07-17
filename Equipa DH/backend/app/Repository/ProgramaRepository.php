<?php

namespace App\Repository;

use App\Models\Programa;
use App\Models\ProgramaHistorico;
use Illuminate\Database\Eloquent\Collection;
use DB;

/**
 * Classe de manipulação da entidade de programa
 *
 * @author Eduardo Praxedes Heinske (eduardo.praxedes@jointecnologia.com.br)
 */
class ProgramaRepository extends BaseRepository
{
    /**
     * Model de Programa
     *
     * @var Programa
     */
    protected $model;

    /**
     * Model de Histórico
     * 
     * @var ProgramaHistorico
     */
    protected $historico;

    protected $cronogramaRepository;
    
    /**
     * Construtor
     *
     * @param Programa $programa
     */
    public function __construct(Programa $programa,
                                ProgramaHistorico $historico,
                                CronogramaRepository $cronogramaRepository)
    {
        $this->model = $programa;
        $this->historico = $historico;
        $this->cronogramaRepository = $cronogramaRepository;
    }

    /**
     * Realiza a pesquisa por programas
     *
     * @param string $search
     * @param array $filtros
     * @param boolean $limit
     * @return Collection
     */
    public function search(string $search = '', array $filtros = [], $limit = false)
    {
        $query = $this->model;

        if ($search) {
            $query = $query->where('nome', 'ilike', '%' . $search . '%');
        }
        
        if (isset($filtros['ativo'])) {
            $query = $query->where('ativo', $filtros['ativo']);
        }

        $query = $query->orderBy('updated_at', 'desc');
        
        if ($limit !== false) {
            return $query->paginate($limit);
        } else {
            return $query->get();
        }
    }

    /**
     * Retorna os programas ativos
     * 
     * @return Collection
     */
    public function getProgramasAtivos()
    {
        return $this->model->where('ativo', true)->orderBy('nome', 'asc')->get();
    }

    /**
     * Busca um programa ativo
     *
     * @param integer $id
     * @return Programa
     */
    public function findAtivo(int $id) : ?Programa
    {
        $this->cronogramaRepository->atualizarSituacaoLote($id);
        return $this->model->with(['cronogramas' => function($q) {
            $q->orderBy('id', 'desc');
        }])->where('ativo', true)->find($id);
    }

    /**
     * Realiza o cadastro / alteração de dados de um programa
     *
     * @param array $data
     * @return boolean
     */
    public function save(array $data) : bool
    {
        try {
            DB::beginTransaction();

            if (isset($data['logomarca'])) {
                $documento = $this->salvarArquivo($data['logomarca'], 'documentos/programas');
                if ($documento && isset($documento['caminho'])) {
                    $data['logomarca'] = $documento['caminho'];
                }
            }

            // Verifica a existência do id para criar ou alterar
            if (isset($data['id']) && $data['id']) {
                // Busca e altera os dados do programa
                $model = $this->model->find($data['id']);
                $model->fill($data)->save();
                $obs = 'Alteração de dados da política pública.';
            } else {
                // Insere a programa
                $this->model->fill($data)->save();  
                $model = $this->model;
                $obs = 'Cadastro de nova política pública.';
            }

            $this->gerarHistorico($model, $obs);

            DB::commit();
            return true;
        } catch (\Exception $ex) {
            report($ex);
            return false;
        }
    }

    /**
     * Altera a situação de ativação de um programa
     *
     * @param integer $id
     * @param boolean $active
     * @return boolean
     */
    public function changeActivate(int $id, bool $active) : bool
    {
        try {
            DB::beginTransaction();

            $model = $this->model->find($id);
            $model->fill([
                'ativo' => $active
            ])->save();

            $this->gerarHistorico($model, 'Alteração de situação da política pública.');

            DB::commit();
            return true;
        } catch (\Exception $ex) {
            report($ex);
            return false;
        }
    }

    /**
     * Remove um programa
     *
     * @param integer $id
     * @return boolean
     */
    public function delete($id) : bool
    {
        try {
            DB::beginTransaction();
            
            $model = $this->model->find($id);
            $model->historicos()->delete();
            $model->delete();

            DB::commit();
            return true;
        } catch (\Exception $ex) {
            report($ex);
            DB::commit();
            return false;
        }
    }

    public function gerarHistorico ($model, $obs) : bool
    {
        try {
            DB::beginTransaction();

            $contexto = ContextoRepository::contextoLogado();

            $this->historico->fill([
                'user_id' => $contexto['user']->id,
                'programa_id' => $model->id,
                'observacao' => $obs,
                'nome' => $model->nome,
                'instituicao_id' => $contexto['user']->instituicao_id,
                'contexto_id' => $contexto['contexto']->contexto_atual_id,
                'descricao' => $model->descricao,
                'logomarca' => $model->logomarca
            ])->save();

            DB::commit();
            return true;
        } catch (\Exception $ex) {
            report($ex);
            DB::commit();
            return false;
        }
    }

    public function getHistorico ($id)
    {
        return $this->historico->where('programa_id', $id)->with([
            'user',
            'instituicao',
            'contexto.unidade',
            'contexto.perfil'
        ])->orderBy('id', 'desc')->get()->toArray();
    }
}