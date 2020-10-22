<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CreateBookRequest;
use App\Http\Requests\BorrowRequest;
use App\Repositories\RepositoryInterface\BookRepositoryInterface;
use App\Repositories\RepositoryInterface\CategoryRepositoryInterface;
use App\Repositories\RepositoryInterface\PublisherRepositoryInterface;
use App\Repositories\RepositoryInterface\BaseRepositoryInterface;
use App\Repositories\RepositoryInterface\BorrowRepositoryInterface;
use Auth;

class BookController extends Controller
{
    protected $book;
    protected $category;
    protected $publisher;
    protected $author;
    protected $borrow;

    public function __construct(
        BookRepositoryInterface $book, 
        CategoryRepositoryInterface $category, 
        PublisherRepositoryInterface $publisher, 
        BaseRepositoryInterface $author,
        BorrowRepositoryInterface $borrow
    ){
        $this->book = $book;
        $this->category = $category;
        $this->publisher = $publisher;
        $this->author = $author;
        $this->borrow = $borrow;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = $this->book->getAll();

        return view('book.books', compact('books'));        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = $this->category->getAllNoPagination();
        $publishers = $this->publisher->getAllPublisher();
        $authors = $this->author->getAllAuthor();

        return view('book.create', compact('categories', 'publishers', 'authors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateBookRequest $request)
    {
        if($this->book->create($request->all())) 
            return redirect()->route('admin.books.index')->with('message', ['msg' => trans('admin.book.create_success'), 'status' => 'success']);

        return redirect()->route('admin.books.index')->with('message', ['msg' => trans('admin.book.create_error'), 'status' => 'error']);               
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = $this->book->find($id);
        if(!$book)
            return abort(404);
        else
            $this->book->viewCounter($id);

        return view('book.detail', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($bookId)
    {
        $book = $this->book->find($bookId);
        $categories = $this->category->getAllNoPagination();
        $publishers = $this->publisher->getPublisher();
        $authors = $this->author->getAuthor();

        return view('book.edit', compact('book', 'categories', 'publishers', 'authors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateBookRequest $request, $bookId)
    {
        if ($this->book->update($bookId, $request->all())) {
            return redirect()->route('admin.books.index')->with('message', ['msg' => trans('admin.book.update_success'), 'status' => 'success']);
        }
        return redirect()->route('admin.books.index')->with('message', ['msg' => trans('admin.book.update_error'), 'status' => 'error']);               
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($bookId)
    {
        $book = $this->book->delete($bookId);
        if (!$book) {
            return redirect()->route('admin.books.index')->with('message', ['msg' => trans('admin.book.delete_error'), 'status' => 'danger']);
        }
        return redirect()->back()->with('message', ['msg' => trans('admin.book.delete_success'), 'status' => 'success']);
    }

    public function borrow(BorrowRequest $request, $id){
        $book = $this->book->find($id);
        $request->merge([
            'book_id' => $id,
            'user_id' => Auth::id(),
        ]);
        if(!$book)
            return abort(404);
        elseif ($book->quantity < 1) 
            return redirect()->route('admin.books.index')->with('message', ['msg' => trans('main.book.not_enough_quantity'), 'status' => 'danger']);
        elseif ($this->borrow->create($request->all()))
            return redirect()->back()->with('message', ['msg' => trans('main.book.borrow.borrow_success'), 'status' => 'success']);
   
        return redirect()->back()->with('message', ['msg' => trans('main.book.borrow.borrow_error'), 'status' => 'danger']);
    }
}
