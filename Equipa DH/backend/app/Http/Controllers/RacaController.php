<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Repository\RacaRepository;
use Illuminate\Http\Request;

class RacaController extends Controller
{
    /**
     * Repository
     *
     * @var RacaRepository
     */
    private $repository;


    /**
     * Construtor
     *
     * @param RacaRepository $repository
     */
    public function __construct(RacaRepository $repository)
    {
        $this->repository = $repository;
    }

    public function combo(Request $request)
    {
        $data = $this->repository->all()->toArray();
        return ResponseHelper::responseSuccess($data, "");
    }
}
