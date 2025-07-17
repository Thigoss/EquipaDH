<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Repository\EscolaridadeRepository;
use Illuminate\Http\Request;

class EscolaridadeController extends Controller
{
    /**
     * Repository
     *
     * @var EscolaridadeRepository
     */
    private $repository;


    /**
     * Construtor
     *
     * @param EscolaridadeRepository $repository
     */
    public function __construct(EscolaridadeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function combo(Request $request)
    {
        $data = $this->repository->all()->toArray();
        return ResponseHelper::responseSuccess($data, "");
    }
}
