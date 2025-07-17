<?php

namespace App\Console\Commands;

use App\Repository\CryptographyRepository;
use Illuminate\Console\Command;

class DatabaseEncrypt extends Command
{
    protected $signature = 'database:encrypt';
    protected $description = 'Comando que criptografa os dados no banco de dados';

    private $repository;

    public function __construct(CryptographyRepository $repository)
    {
        $this->repository = $repository;
        parent::__construct();
    }

    public function handle()
    {
        $this->repository->encrypt();
    }
}
