<?php
namespace App\Repositories;
use App\Repositories\RepositoryInterface\BorrowRepositoryInterface;
use App\Models\Borrow;

class BorrowRepository implements BorrowRepositoryInterface
{
    public function getAll()
    {
        return Borrow::paginate(config('app.paginate'));
    }

    public function find($id)
    {
        return Borrow::find($id);
    }

    public function create($attributes = []){
        return Borrow::create($attributes);
    }

    public function update($id, $attributes = [])
    {
        $borrow = Borrow::find($id);
        if($borrow){
            $borrow->update($attributes);
            
            return true;
        }
        
        return false;
    }

    public function delete($id)
    {
        $borrow = Borrow::find($id);
        if ($borrow) {
            $borrow->delete();

            return true;
        }

        return false;
    }
}
