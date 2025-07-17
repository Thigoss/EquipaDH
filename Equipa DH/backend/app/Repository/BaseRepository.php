<?php

namespace App\Repository;

use App\Repository\IRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Classe base de repositório
 *
 * @author Eduardo Praxedes Heinske (eduardo.praxedes@jointecnologia.com.br)
 */
abstract class BaseRepository implements IRepository
{
    /**
     * Model específica do repositoru em questão
     *
     * @var Model
     */
    protected $model;

    /**
     * Busca de um item da model
     *
     * @param integer $id
     * @return Model
     */
    public function find(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * Busca de itens baseada em condição
     *
     * @param array $where
     * @return Model
     */
    public function findBy(array $where = [])
    {
        return $this->model->where($where)->get();
    }

    /**
     * Retorno de todos os itens da model
     *
     * @param array $orderBy
     * @return Collection
     */
    public function all(array $orderBy = [])
    {
        if ($orderBy) {
            $all =  $this->model->orderBy($orderBy[0], $orderBy[1])->get();
        } else {
            $all = $this->model->all();
        }

        return $all;
    }

    /**
     * Retorno de todos os itens da model por paginação
     *
     * @param integer $limit
     * @param integer $offset
     * @return Collection
     */
    public function allPaginate(int $limit, int $offset)
    {
        return $this->model->take($limit)->skip($offset);
    }

    /**
     * Método padrão de save
     *
     * @param array $data
     * @return boolean
     */
    public function save(array $data) 
    {
        $data = $this->beforeSave($data);

        if (!isset($data['id']) || empty($data['id'])) {
            $model = $this->model->fill($data);
        } else {
            $model = $this->model->find($data['id'])->fill($data);
        }

        $model->save();

        return $this->afterSave($model, $data);
    }

    /**
     * Método padrão de delete
     *
     * @param integer $id
     * @return boolean
     */
    public function delete(int $id) : bool
    {
        return $this->model->find($id)->delete();
    }

    /**
     * Salva arquivo
     *
     * @param $arquivo
     * @return array
     */
    public function salvarArquivo($arquivo, $diretorio) : array
    {
        try {
            if (isset($arquivo)) {
                if (strpos($arquivo, ';') !== false) {
                    $posicao = strpos($arquivo, ';');
                    $extensao = substr($arquivo, 0, $posicao);

                    $posicaoExt = strpos($extensao, '/');
                    $ext = substr($extensao, $posicaoExt + 1);
                    
                    $ext = ($ext == 'jpeg') ? 'jpg' : $ext;
                    $ext = ($ext == 'vnd.openxmlformats-officedocument.wordprocessingml.document') ? 'docx' : $ext;
                    $ext = ($ext == 'msword') ? 'doc' : $ext;
                    $ext = ($ext == 'vnd.ms-excel') ? 'xls' : $ext;
                    $ext = ($ext == 'vnd.openxmlformats-officedocument.spreadsheetml.sheet') ? 'xlsx' : $ext;
                    $ext = ($ext == 'vnd.ms-powerpoint') ? 'ppt' : $ext;
                    $ext = ($ext == 'vnd.openxmlformats-officedocument.presentationml.presentation') ? 'pptx' : $ext;
                    
                    $urlFile = $diretorio . DIRECTORY_SEPARATOR . time() . rand(0, 9999999) . rand(0, 9999999) . '.' . $ext;

                    $file = str_replace($extensao . ';base64,', '', $arquivo);
                    $file = base64_decode($file);

                    if (!file_exists($diretorio)) {
                        mkdir($diretorio, 0755, true);
                    }

                    file_put_contents($urlFile, $file);
                    return ['success' => true, 'arquivo' => asset($urlFile), 'caminho' => $urlFile];
                } else {
                    return ['success' => false, 'arquivo' => null];
                }
                
            }
        } catch (\Exception $ex) {
            report($ex);
            return ['success' => false];
        }
    }

    /**
     * Deleta arquivo fisico
     *
     * @param $caminho
     * @return bool
     */
    public function deleteFileDisco($caminho) : bool
    {
        $caminho = explode('/', $caminho);
        $remove = unlink('../public/' .$caminho[0]. '/' . $caminho[1] . '/' . $caminho[2]);

        if ($remove) {
            return true;
        } else {
            return false;
        }
    }

    public function beforeSave($data)
    {
        return $data;
    }

    public function afterSave($model, $data)
    {
        return true;
    }

   /* public function paginator($resultados, $total, $totalPorPagina)
    {
         // Construir o paginador personalizado
         $paginador = new LengthAwarePaginator(
            $resultados,
            $total,
            $porPagina,
            LengthAwarePaginator::resolveCurrentPage(),
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
    }*/

}
