<?php 

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use App\Repository\CidadeRepository;
use App\Models\Estado;
use App\Models\Cidade;

/**
 * Controller com os endpoints referentes a cidade
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class CidadeController extends Controller
{
    /**
     * Repository de cidade
     *
     * @var CidadeRepository
     */
    private $repository;

    /**
     * Construtor
     *
     * @param CidadeRepository $repository
     */
    public function __construct(CidadeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        try {
            $data = [];
            $busca = $request->get('filter') ?? '';
            $filtros = $request->toArray();
            if ($request->has('length')) {
                $data["data"] = $this->repository->search($busca, $filtros, $request->get('length'));
            } else {
                $data['data'] = $this->repository->search($busca, $filtros);
            }
            return ResponseHelper::responseSuccess($data, "");
        } catch (\Exception $ex) {
            return ResponseHelper::responseError([], $ex->getMessage());
        }
    }

     /**
     * @param null $id
     * @return array|\Illuminate\Http\JsonResponseG
     * @info Retorna as cidades a partir de um id de estado, ou todas as cidades
     */
    public function getCidades($id = 0)
    {
        $cidades = $this->repository->getByEstadoId($id);

        return ResponseHelper::responseSuccess(['cidades' => $cidades], "", true, 200);
    }

    public function getByEstadoId($estadoId)
    {
        return ResponseHelper::responseSuccess($this->repository->getByEstadoId($estadoId), "");
    }
}
