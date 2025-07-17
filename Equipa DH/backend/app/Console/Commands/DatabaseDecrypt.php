<?php

namespace App\Console\Commands;

use App\Repository\CryptographyRepository;
use Illuminate\Console\Command;

class DatabaseDecrypt extends Command
{
    protected $signature = 'database:decrypt';
    protected $description = 'Comando que descriptografa os campos no banco de dados';

    private $repository;

    public function __construct(CryptographyRepository $repository)
    {
        $this->repository = $repository;
        parent::__construct();
    }

    public function handle()
    {
        $this->repository->decrypt();
    }
}
