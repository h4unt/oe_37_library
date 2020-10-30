<?php
namespace App\Repositories;
use App\Repositories\RepositoryInterface\LikeRepositoryInterface;
use App\Models\Like;

class LikeRepository implements LikeRepositoryInterface
{
    public function getAll()
    {
        //
    }

    public function find($id)
    {
        return Like::find($id);
    }

    public function findExist($user_id, $book_id){
        return Like::where('user_id', $user_id)
                    ->where('book_id', $book_id)
                    ->first();
    }
    
    public function unLike($user_id, $book_id){
        $like = $this->findExist($user_id, $book_id);
        if ($like) {
            $like->delete();

            return true;
        }

        return false;
    }
    public function create($attributes = []){
        return Like::create($attributes);
    }

    public function update($id, $attributes = [])
    {
        //
    }

    public function delete($id)
    {
        $like = Like::find($id);
        if($like){
            $like->delete();

            return true;
        }
        return false;
    }
}
