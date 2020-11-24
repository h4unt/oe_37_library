<?php

namespace App\Repositories\RepositoryInterface;

interface BorrowRepositoryInterface extends BaseRepositoryInterface
{
    public function getQuerySearch($fullname, $role);
    public function getHistoryBorrow($userId);
    public function countMonth();
    public function getDataMonth();
    public function countQuarter();
    public function getLabelsQuarter();
    public function countYear();
    public function getDataYear();
}
