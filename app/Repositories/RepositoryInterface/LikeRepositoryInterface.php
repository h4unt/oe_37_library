<?php
namespace App\Repositories\RepositoryInterface;

interface LikeRepositoryInterface extends BaseRepositoryInterface
{
    public function findExist($user_id, $book_id);
    public function unLike($user_id, $book_id);
}
