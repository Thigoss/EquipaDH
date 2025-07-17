<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Repository\OrientacaoSexualRepository;
use Illuminate\Http\Request;

class OrientacaoSexualController extends Controller
{
    /**
     * Repository
     *
     * @var OrientacaoSexualRepository
     */
    private $repository;


    /**
     * Construtor
     *
     * @param OrientacaoSexualRepository $repository
     */
    public function __construct(OrientacaoSexualRepository $repository)
    {
        $this->repository = $repository;
    }

    public function combo(Request $request)
    {
        $data = $this->repository->all()->toArray();
        return ResponseHelper::responseSuccess($data, "");
    }
}
