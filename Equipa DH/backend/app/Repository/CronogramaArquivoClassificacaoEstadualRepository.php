<?php

namespace App\Repository;

use App\Models\CronogramaArquivoClassificacaoEstadual;
use Illuminate\Support\Facades\DB;

class CronogramaArquivoClassificacaoEstadualRepository extends BaseRepository
{

    protected $model;

    public function __construct(CronogramaArquivoClassificacaoEstadual $model)
    {
        $this->model = $model;
    }

    public function search(string $search = '', array $filtros = [], $limit = false)
    {
        $query = $this->model;

        if ($search) {
            $query = $query->where('nome', 'ilike', '%' . $search . '%');
        }

        if ($filtros['cronograma']) {
            $query = $query->where('cronograma_id', $filtros['cronograma']);
        }

        $query->orderBy('id', 'desc');
        
        if ($limit !== false) {
            return $query->paginate($limit);
        } else {
            return $query->get();
        }
    }

    public function beforeSave($data)
    {
        if ($data['arquivo']) {
            $documento = $this->salvarArquivo($data['arquivo'], 'documentos/classificacaoestadual');
            if ($documento && $documento['caminho']) {
                $data['arquivo'] = $documento['caminho'];
            }
        }

        return $data;
    }

    public function switchStatus($id)
    {
        try {
            DB::beginTransaction();
            $model = $this->model->find($id);

            if ($model) {
                $model->ativo = !$model->ativo;
                $model->save();
            }

            if ($model->ativo) $this->model->where('id', '!=', $id)->update(['ativo' => false]);

            DB::commit();
            return ['success' => true, 'message' => 'Arquivo de Classificação ' . ($model->ativo ? 'ativado' : 'inativado') . ' com sucesso!'];
        } catch (\Exception $ex) {
            DB::rollBack();
            return ['success' => false, 'message' => $ex->getMessage()];
        }
    }
}