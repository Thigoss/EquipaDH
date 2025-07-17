<?php

namespace App\Repository;

use App\Exports\UnidadeExport;
use App\Models\Unidade;
use App\Models\UnidadeResponsavel;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use DB;
use Excel;
use PDF;

/**
 * Classe de manipulação da entidade de unidade
 *
 * @author Eduardo Praxedes Heinske (eduardo.praxedes@jointecnologia.com.br)
 */
class UnidadeRepository extends BaseRepository
{

    /**
     * Model de 
     *
     * @var User
     */
    protected $model;
    
    /**
     * Model de unidade responsável
     * 
     * @var UnidadeResponsavel
     */
    protected $responsavel;
    
    /**
     * Construtor
     *
     * @param User $user
     */
    public function __construct(Unidade $unidade, UnidadeResponsavel $responsavel)
    {
        $this->model = $unidade;
        $this->responsavel = $responsavel;
    }


    /**
     * Realiza a pesquisa por unidades
     *
     * @param string $search
     * @param array $filtros
     * @param boolean $limit
     * @return Collection
     */
    public function search(string $search = '', array $filtros = [], $limit = false)
    {
        $query = $this->model->with(['estado', 'cidade']);

        if ($search) {
            $query = $query->where('nome', 'ilike', '%' . $search . '%');
        }
        
        if (isset($filtros['estado_id']) && !empty($filtros['estado_id'])) {
            $query = $query->where('estado_id', $filtros['estado_id']);
        }
        
        if (isset($filtros['cidade_id']) && !empty($filtros['cidade_id'])) {
            $query = $query->where('cidade_id', $filtros['cidade_id']);
        }
        
        if (isset($filtros['ativo'])) {
            $query = $query->where('ativo', $filtros['ativo']);
        }

        $query->orderBy('updated_at', 'DESC');

        if ($limit !== false) {
            return $query->paginate($limit);
        } else {
            return $query->get();
        }
    }

    /**
     * Obtem os dados de uma unidade específicoa
     *
     * @param integer $id
     * @return User
     */
    public function find($id) : Unidade
    {
        return $this->model->with(['responsaveis'])->where('id', $id)->first();
    }

    public function all(array $orderBy = [])
    {
        if ($orderBy) {
            $all =  $this->model->where('ativo', true)->orderBy($orderBy[0], $orderBy[1])->get();
        } else {
            $all = $this->model->where('ativo', true)->all();
        }

        return $all;
    }

    /**
     * Realiza o cadastro / alteração de dados de uma unidade
     *
     * @param array $data
     * @return boolean
     */
    public function save(array $data) : bool
    {
        try {
            DB::beginTransaction();

            // Verifica a existência do id para criar ou alterar
            if (isset($data['id']) && $data['id']) {
                // Busca e altera os dados da unidade
                $model = $this->model->find($data['id']);
                $model->fill($data)->save();

                // Remove os responsáveis que não estão na lista
                $model->responsaveis()->whereNotIn('id', array_column($data['responsaveis'], 'id'))->delete();

                // Cadastra os responsáveis que não possuírem id
                foreach ($data['responsaveis'] as $responsavel) {
                    if (!isset($responsavel['id']) || !$responsavel['id']) {
                        $responsavel['unidade_id'] = $model->id;
                        $modelResponsavel = new UnidadeResponsavel();
                        $modelResponsavel->fill($responsavel)->save();
                    } else {
                        // Altera os responsáveis
                        $modelResponsavel = $this->responsavel->find($responsavel['id']);
                        $modelResponsavel->fill($responsavel)->save();
                    }
                }
            } else {
                // Insere a unidade
                $data['ativo'] = true;
                $this->model->fill($data)->save();  
                $model = $this->model;

                // Cadastra os responsáveis
                if (isset($data['responsaveis']) && is_array($data['responsaveis'])) {
                    foreach ($data['responsaveis'] as $responsavel) {
                        $responsavel['unidade_id'] = $model->id;
                        $modelResponsavel = new UnidadeResponsavel();
                        $modelResponsavel->fill($responsavel)->save();
                    }
                }
            }


            DB::commit();
            return true;
        } catch (\Exception $ex) {
            report($ex);
            return false;
        }
    }

    /**
     * Altera a situação de ativação de um usuário
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

            DB::commit();
            return true;
        } catch (\Exception $ex) {
            report($ex);
            return false;
        }
    }

    /**
     * Remove um usuário
     *
     * @param integer $id
     * @return boolean
     */
    public function delete($id) : bool
    {
        try {
            DB::beginTransaction();
            
            // Busca a unidade, remove seus responsáveis e a unidade
            $model = $this->model->find($id);
            $model->responsaveis()->delete();
            $model->delete();

            DB::commit();
            return true;
        } catch (\Exception $ex) {
            report($ex);
            DB::rollBack();
            return false;
        }
    }

    public function exportExcel(array $filtros = [])
    {
        ini_set('memory_limit', '2G');
        set_time_limit(0);

        $dados = $this->search(isset($filtros['filter']) ? $filtros['filter'] : '', $filtros)->toArray();
        return Excel::download(new UnidadeExport($dados), 'unidades.xlsx');
    }

    public function exportPDF(array $filtros = [])
    {
        ini_set('memory_limit', '2G');
        set_time_limit(0);

        $dados = $this->search(isset($filtros['filter']) ? $filtros['filter'] : '', $filtros)->toArray();
        $pdf = PDF::loadView("unidade.export-pdf-search", [
            'filtros' => $filtros,
            'data'  => $dados
        ]);

        return $pdf->download('unidades.pdf');
    }
}