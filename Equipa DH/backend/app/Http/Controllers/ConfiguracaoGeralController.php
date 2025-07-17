<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\ConfiguracaoGeralRequest;
use App\Repository\ConfiguracaoGeralRepository;

/**
 * Controller com os endpoints referentes a configuração geral
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class ConfiguracaoGeralController extends Controller
{
  
    /**
     * Repository de configuração geral
     *
     * @var ConfiguracaoGeralRepository
     */
    private $repository;

    /**
     * Construtor
     *
     * @param ConfiguracaoGeralRepository $repository
     */
    public function __construct(ConfiguracaoGeralRepository $repository)
    {
        $this->repository = $repository;
    }

    public function show()
    {
        try {
            $cronograma = $this->repository->getConfiguracao();

            if ($cronograma) {
                return ResponseHelper::responseSuccess($cronograma->toArray(), "");
            } else {
                return ResponseHelper::responseSuccess([], "Configuração não encontrado!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function store(ConfiguracaoGeralRequest $request)
    {
        try {
            $data = $request->toArray();

            if ($this->repository->save($data)) {
                    return ResponseHelper::responseSuccess([],  'Configuração inserida com sucesso!');
            } else {
                return ResponseHelper::responseError([], "Falha ao inserir configuração!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

    public function update(ConfiguracaoGeralRequest $request, $id)
    {
        try {
            $data = $request->toArray();
            $data['id'] = $id;

            if ($this->repository->save($data)) {
                return ResponseHelper::responseSuccess([], "Configuração alterada com sucesso!");
            } else {
                return ResponseHelper::responseError([], "Falha ao alterar configuração!");
            }
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }
}
