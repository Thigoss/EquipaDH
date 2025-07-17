<?php 

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Repository\EstadoRepository;
use Illuminate\Http\Response;

/**
 * Controller com os endpoints referentes a estado
 *
 * @author Eduardo Praxedes Heinske <eduardo.praxedes@jointecnologia.com.br>
 */
class EstadoController extends Controller
{
    /**
     * Repository de estado
     *
     * @var EstadoRepository
     */
    private $repository;

    /**
     * Construtor
     *
     * @param EstadoRepository $repository
     */
    public function __construct(EstadoRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Post(
     *     tags={"estado"},
     *     summary="Lista os estados",
     *     description="Retorna uma lista com todos os estados do Brasil",
     *     path="/api/estado",
     *     @OA\Response(response="200", description="Estados retornados com sucesso."),
     *     @OA\Response(response="500", description="Falha na requisição."),
     * ),
     *
    */
    public function index()
    {
        return ResponseHelper::responseSuccess($this->repository->all()->toArray(), "");
    }
}
