<?php

namespace App\Http\Controllers;

use App\Repository\DownloadRepository;

class DownloadController extends Controller
{
    /**
     * Repository
     *
     * @var DownloadRepository
     */
    private $repository;

    /**
     * Construtor
     *
     * @param DownloadRepository $repository
     */
    public function __construct(DownloadRepository $repository)
    {
        $this->repository = $repository;
    }

    public function download($origin, $id)
    {
        try {
            return $this->repository->download($origin, $id);
        } catch (\Exception $ex) {
            return view('defaults.error', ['message' => $ex->getMessage()]);
        }
    }
}
