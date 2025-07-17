<?php 

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Repository\AuditAuxRepository;

/**
 * Controller para a responsábilidade definida a cidades
 *
 * @author Ezequiel Lafuente <ezequiel.coelho@jointecnologia.com.br>
 */
class AuxAuditController extends Controller
{

    protected $repository;

    public function __construct(AuditAuxRepository $repository)
    {
        $this->repository = $repository;
    }

    public function comboLabels()
    {
        $dados = $this->repository->comboLabels();
        return ResponseHelper::responseSuccess($dados, "", true, 200);
    }
}
