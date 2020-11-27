<?php
namespace App\Repositories\RepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function getQuerySearch($fullname, $role);
    public function userCountChart();
}
