<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Validation\CronogramaArquivoValidation;
use App\Repository\CronogramaArquivoClassificacaoEstadualRepository;
use App\Repository\CronogramaArquivoClassificacaoMunicipalRepository;
use Illuminate\Http\Request;

class CronogramaArquivoController extends Controller
{
    const ESTADUAL = 'estadual';
    const MUNICIPAL = 'municipal';

    private $estadual;
    private $municipal;
    private $validation;

    public function __construct(CronogramaArquivoClassificacaoEstadualRepository $estadual,
                                CronogramaArquivoClassificacaoMunicipalRepository $municipal,
                                CronogramaArquivoValidation $validation)
    {
        $this->estadual = $estadual;
        $this->municipal = $municipal;
        $this->validation = $validation;
    }

    public function repositoryAlternator($tipo)
    {
        if ($tipo == self::ESTADUAL) return $this->estadual;
        else if ($tipo == self::MUNICIPAL) return $this->municipal;
        else throw new \Exception("Tipo de classificação inválido!");
    }

    public function index(Request $request, $tipo, $cronograma)
    {
        $repo = $this->repositoryAlternator($tipo);

        try {
            //Realiza a busca dos cronogramas
            $instituicoes = [];
            $busca = $request->get('filter') ?? '';
            $filtros = $request->toArray();
            $filtros['cronograma'] = $cronograma;

            if ($request->has('length')) {
                $instituicoes["data"] = $repo->search($busca, $filtros, $request->get('length'));
            } else {
                $instituicoes['data'] = $repo->search($busca, $filtros);
            }

            return ResponseHelper::responseSuccess($instituicoes, "");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function show($id, $tipo)
    {
        $repo = $this->repositoryAlternator($tipo);

        try {
            $cronograma = $repo->find($id);

            if ($cronograma) {
                return ResponseHelper::responseSuccess($cronograma->toArray(), "");
            } else {
                return ResponseHelper::responseSuccess([], "Arquivo de Classificação não encontrado!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function store(Request $request, $tipo)
    {
        $repo = $this->repositoryAlternator($tipo);

        try {
            $data = $request->toArray();
            $validation = $this->validation->validateFormat($data['arquivo'], $tipo);

            if ($validation['success']) {

                if ($repo->save($data)) {
                      return ResponseHelper::responseSuccess([], 'Arquivo de Classificação inserido com sucesso!');
                } else {
                    return ResponseHelper::responseError([], "Falha ao inserir arquivo de classificação!");
                }
            } else {
                return response()->json($validation);
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function update(Request $request, $tipo, $id)
    {
        $repo = $this->repositoryAlternator($tipo);

        try {
            $data = $request->toArray();
            $validation = $this->validation->validateFormat($data['arquivo'], $tipo);

            if ($validation['success']) {
                $data['id'] = $id;

                if ($repo->save($data)) {
                    return ResponseHelper::responseSuccess([], "Arquivo de classificação alterado com sucesso!");
                } else {
                    return ResponseHelper::responseError([], "Falha ao alterar arquivo de classificação!");
                }
            } else {
                return response()->json($validation);
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function destroy($tipo, $id)
    {
        $repo = $this->repositoryAlternator($tipo);

        try {
            if ($repo->delete($id)) {
                return ResponseHelper::responseSuccess([], "Arquivo de Classificação excluído com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao excluir arquivo de classificação!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function switchStatus($tipo, $id)
    {
        try {
            $repo = $this->repositoryAlternator($tipo);
            $result = $repo->switchStatus($id);
            if ($result['success']) {
                return ResponseHelper::responseSuccess([], $result['message']);
            } else {
                return ResponseHelper::responseError([], $result['message']);
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

}
