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
use App\Repositories\RepositoryInterface\ReviewRepositoryInterface;
use App\Repositories\RepositoryInterface\LikeRepositoryInterface;
use Auth;

class BookController extends Controller
{
    protected $book;
    protected $category;
    protected $publisher;
    protected $author;
    protected $review;
    protected $borrow;
    protected $like;

    public function __construct(
        BookRepositoryInterface $book, 
        CategoryRepositoryInterface $category, 
        PublisherRepositoryInterface $publisher, 
        BaseRepositoryInterface $author,
        ReviewRepositoryInterface $review,
        BorrowRepositoryInterface $borrow,
        likeRepositoryInterface $like
    ){
        $this->book = $book;
        $this->category = $category;
        $this->publisher = $publisher;
        $this->author = $author;
        $this->review = $review;
        $this->borrow = $borrow;
        $this->like = $like;
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
        if (!$book) {
            return abort(404);
        } else {
            $this->book->viewCounter($id);
        }
        $liked = $this->like->findExist(Auth::id(), $id);
        $reviews = $this->review->getWithUser($id);

        return view('book.detail', compact('book', 'reviews', 'liked'));
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

    public function like(Request $request){
        $book = $this->book->find($request->book_id);
        if (!Auth::check())
            return response()->json([
                'msg' => trans('main.login_msg'),
                'status' => 'warning',
            ]);
        elseif($book) { // book exist
            if (!$this->like->findExist(Auth::id(), $request->book_id)) { // like
                $this->like->create([
                    'book_id' => $request->book_id,
                    'user_id' => Auth::id()
                ]);

                return response()->json([
                    'msg' => trans('main.book.like_success'),
                    'btn_content' => '<i class="fa fa-thumbs-down" aria-hidden="true"></i> '. trans('main.book.unlike_book'),
                    'status' => 'success',
                ]);
            }else{ // unlike
                $this->like->unlike(Auth::id(),$request->book_id);

                return response()->json([
                    'msg' => trans('main.book.unlike_success'),
                    'btn_content' => '<i class="fa fa-thumbs-up" aria-hidden="true"></i> '. trans('main.book.like_book'),
                    'status' => 'success',
                ]);
            }
        } else
            return response()->json([
                'msg' => trans('main.book.not_exist'),
                'status' => 'error',
            ]);        
    }
}
