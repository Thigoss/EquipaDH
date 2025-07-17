<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Repository\DeficienciaRepository;
use Illuminate\Http\Request;

class DeficienciaController extends Controller
{
    /**
     * Repository
     *
     * @var DeficienciaRepository
     */
    private $repository;


    /**
     * Construtor
     *
     * @param DeficienciaRepository $repository
     */
    public function __construct(DeficienciaRepository $repository)
    {
        $this->repository = $repository;
    }

    public function combo(Request $request)
    {
        $data = $this->repository->all(['nome', 'asc'])->toArray();
        return ResponseHelper::responseSuccess($data, "");
    }
}
