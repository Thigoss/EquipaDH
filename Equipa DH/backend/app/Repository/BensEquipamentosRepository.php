<?php

namespace App\Repository;

use App\Exports\BensEquipamentosExport;
use App\Models\BemEquipamento;
use Illuminate\Database\Eloquent\Collection;
use DB;
use Excel;
use Exception;

/**
 * Classe de manipulação da entidade de adesões
 *
 * @author Eduardo Praxedes Heinske (eduardo.praxedes@jointecnologia.com.br)
 */
class BensEquipamentosRepository extends BaseRepository
{

  /**
   * Model de Bens e Equipamentos
   *
   * @var BemEquipamento
   */
  protected $model;


  /**
   * Construtor
   *
   * @param BemEquipamento $bemEquipamento
   */
  public function __construct(BemEquipamento $bemEquipamento)
  {
    $this->model = $bemEquipamento;
  }

  /**
   * Realiza a pesquisa por bens e equipamentos
   *
   * @param string $search
   * @param array $filtros
   * @param boolean $limit
   * @return Collection
   */
  public function search(array $filtros = [], $limit = false)
  {
    $query = $this->model;

    if (!empty($filtros['nome'])) {
      $query = $query->where('nome', 'like', "%{$filtros['nome']}%");
    }

    if (!empty($filtros['categoria'])) {
      $query = $query->where('categoria', $filtros['categoria']);
    }

    if (!empty($filtros['descricao'])) {
      $query = $query->where('descricao', $filtros['descricao']);
    }

    if (!empty($filtros['situacao'])) {
      $query = $query->where('situacao', $filtros['situacao']);
    }

    if ($limit !== false) {
      return $query->paginate($limit);
    } else {
      return $query->get();
    }
  }

  /**
   * Busca os dados de uma adesão
   * 
   * @param  $id
   * @return BemEquipamento
   */
  public function find($id): ?BemEquipamento
  {
    return $this->model->find(intval($id));
  }

  /**
   * Realiza o cadastro / alteração de dados de uma adesão
   *
   * @param array $data
   * @return boolean
   */
  public function save(array $data): bool
  {
    try {
      DB::beginTransaction();


      // Verifica a existência do id para criar ou alterar
      if (isset($data['id']) && $data['id'] && $data['id']!= null) {
      
        // Busca e altera os dados da adesão
        $model = $this->model->find($data['id']);
        
        $model->fill($data)->save();
      } else {
    
        $this->model->fill($data)->save();
        $model = $this->model;
      }

      $model->save();

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
                'situacao' => $active
            ])->save();

            DB::commit();
            return true;
        } catch (\Exception $ex) {
            report($ex);
            return false;
        }
    }

  /**
   * Remove uma bem
   *
   * @param integer $id
   * @return boolean
   */
  public function delete($id): bool
  {
    try {
      DB::beginTransaction();

      // Busca o bem, remove 
      $model = $this->model->find($id);
      $model->delete();

      DB::commit();
      return true;
    } catch (\Exception $ex) {
      report($ex);
      DB::commit();
      return false;
    }
  }


  public function exportBensToExcel(array $dados = [])
  {
    ini_set('memory_limit', '2G');
    set_time_limit(0);

    return Excel::download(new BensEquipamentosExport($dados), 'bens-equipamentos.xlsx');
  }
}
