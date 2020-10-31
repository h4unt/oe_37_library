<?php
namespace App\Repositories\RepositoryInterface;

interface CategoryRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllNoPagination();

    public function getParent();

    public function getWithKey($key); 
}
