<?php

namespace App\Repository;

use App\Exports\InstituicaoExport;
use App\Models\Instituicao;
use App\Models\InstituicaoRepresentante;
use Illuminate\Database\Eloquent\Collection;
use DB;
use Excel;
use PDF;

/**
 * Classe de manipulação da entidade de instituição
 *
 * @author Eduardo Praxedes Heinske (eduardo.praxedes@jointecnologia.com.br)
 */
class InstituicaoRepository extends BaseRepository
{

    /**
     * Model de Instituição
     *
     * @var Instituicao
     */
    protected $model;
    
    /**
     * Model de Representante
     * 
     * @var InstituicaoRepresentante
     */
    protected $representantes;
    
    /**
     * Construtor
     *
     * @param Instituicao $instituicao
     * @param InstituicaoRepresentante $representantes
     */
    public function __construct(Instituicao $instituicao, InstituicaoRepresentante $representantes)
    {
        $this->model = $instituicao;
        $this->representantes = $representantes;
    }

    /**
     * Realiza a pesquisa por instituições
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
            $query = $query->where('razao_social', 'ilike', '%' . $search . '%');
        }

        if (isset($filtros['cnpj']) && !empty($filtros['cnpj'])) {
            $query = $query->where('cnpj', 'ilike', '%' . $filtros['cnpj'] . '%');
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

        $query = $query->orderBy('razao_social', 'asc');

        if ($limit !== false) {
            return $query->paginate($limit);
        } else {
            return $query->get();
        }
    }

    /**
     * Obtem os dados de uma instituição específica
     *
     * @param integer $id
     * @return Instituicao
     */
    public function find($id) : Instituicao
    {
        return $this->model->with(['representantes', 'users'])->where('id', $id)->first();
    }

    public function all(array $orderBy = [])
    {
        if ($orderBy) {
            $all =  $this->model->with(['estado'])->orderBy($orderBy[0], $orderBy[1])->get();
        } else {
            $all = $this->model->all();
        }

        return $all;
    }

    /**
     * Obtem os dados de uma instituição específica pelo CNPJ
     *
     * @param string $cnpj
     * @return Instituicao
     */
    public function findByCnpj($cnpj) : ?Instituicao
    {
        return $this->model->where('cnpj', $cnpj)->first();
    }

    /**
     * Realiza o cadastro / alteração de dados de uma instituição
     *
     * @param array $data
     * @return boolean|int
     */
    public function save(array $data)
    {
        try {
            DB::beginTransaction();

            if (isset($data['autoridade_rg'])) {
                $documento = $this->salvarArquivo($data['autoridade_rg'], 'documentos/users');
                if ($documento && isset($documento['caminho'])) {
                    $data['autoridade_rg'] = $documento['caminho'];
                }
            }

            if (isset($data['autoridade_cpf'])) {
                $documento = $this->salvarArquivo($data['autoridade_cpf'], 'documentos/users');
                if ($documento && isset($documento['caminho'])) {
                    $data['autoridade_cpf'] = $documento['caminho'];
                }
            }

            if (isset($data['autoridade_ato_posse'])) {
                $documento = $this->salvarArquivo($data['autoridade_ato_posse'], 'documentos/users');
                if ($documento && isset($documento['caminho'])) {
                    $data['autoridade_ato_posse'] = $documento['caminho'];
                }
            }

            if (isset($data['autoridade_ato_delegacao'])) {
                $documento = $this->salvarArquivo($data['autoridade_ato_delegacao'], 'documentos/users');
                if ($documento && isset($documento['caminho'])) {
                    $data['autoridade_ato_delegacao'] = $documento['caminho'];
                }
            }

            // Verifica a existência do id para criar ou alterar
            if (isset($data['id']) && $data['id']) {
                // Busca e altera os dados da instituição
                $model = $this->model->find($data['id']);
                $model->fill($data)->save();

                if (isset($data['responsaveis'])) {
                    $model->representantes()->whereNotIn('id', array_column($data['responsaveis'], 'id'))->delete();

                    // Cadastra os responsáveis que não possuírem id
                    foreach ($data['responsaveis'] as $responsavel) {
                        if (!isset($responsavel['id']) || !$responsavel['id']) {
                            $responsavel['instituicao_id'] = $model->id;
                            $modelRepresentante = new InstituicaoRepresentante();
                            $modelRepresentante->fill($responsavel)->save();
                        } else {
                            $modelRepresentante = $this->representantes->find($responsavel['id']);
                            $modelRepresentante->fill($responsavel)->save();
                        }
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
                        $responsavel['instituicao_id'] = $model->id;
                        $modelRepresentante = new InstituicaoRepresentante();
                        $modelRepresentante->fill($responsavel)->save();
                    }
                }
            }

            DB::commit();
            return $model->id;
        } catch (\Exception $ex) {
            report($ex);
            return false;
        }
    }

    /**
     * Altera a situação de ativação de uma instituição
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
     * Remove uma instituição
     *
     * @param integer $id
     * @return boolean
     */
    public function delete($id) : bool
    {
        try {
            DB::beginTransaction();
            
            // Busca a instituição, remove seus representantes e a instituição
            $model = $this->model->find($id);
            $model->representantes()->delete();
            $model->delete();

            DB::commit();
            return true;
        } catch (\Exception $ex) {
            report($ex);
            DB::commit();
            return false;
        }
    }

    public function exportExcel(array $filtros = [])
    {
        ini_set('memory_limit', '2G');
        set_time_limit(0);
        
        $dados = $this->search(isset($filtros['filter']) ? $filtros['filter'] : '', $filtros)->toArray();
        return Excel::download(new InstituicaoExport($dados), 'instituicoes.xlsx');
    }

    public function exportPDF(array $filtros = [])
    {
        ini_set('memory_limit', '2G');
        set_time_limit(0);

        $dados = $this->search(isset($filtros['filter']) ? $filtros['filter'] : '', $filtros)->toArray();

        $pdf = PDF::loadView("instituicao.export-pdf-search", [
            'filtros' => $filtros,
            'data'  => $dados
        ])->setPaper('a4', 'landscape');

        return $pdf->download('instituicoes.pdf');
    }
}