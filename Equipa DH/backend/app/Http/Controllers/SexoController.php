<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Repository\SexoRepository;
use Illuminate\Http\Response;

class SexoController extends Controller
{
    /**
     * Repository
     *
     * @var SexoRepository
     */
    private $repository;

    /**
     * Construtor
     *
     * @param SexoRepository $repository
     */
    public function __construct(SexoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function combo()
    {
        $data = $this->repository->all()->toArray();
        return ResponseHelper::responseSuccess($data, "");
    }
}
