<?php

namespace App\Repository;


/**
 * Interface padrão de repositório
 * 
 * @author Eduardo Praxedes Heinske (eduardo.praxedes@jointecnologia.com.br)
 */
interface IRepository {

    public function find(int $id);

    public function all();

    public function allPaginate(int $limit, int $offset);

    public function save(array $data);

    public function delete(int $id) : bool;
}
