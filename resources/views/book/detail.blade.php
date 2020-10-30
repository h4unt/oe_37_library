@extends('layouts.app')
@section('title', trans($book->book_title))
@section('content')
<div class="container mt-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">@lang('main.home')</a></li>
            <li class="breadcrumb-item"><a href="#">{{ $book->category->cate_name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $book->book_title }}</li>
        </ol>
    </nav>
    @if (session('message'))
    <div class="alert alert-{{ session('message.status') }} mb-4 mt-4">
        {{ session('message.msg') }}
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger mb-4">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="row no-gutters">
        <div class="col-md-4">
            @if (file_exists( public_path().'/images/books/'.$book->book_image ) && $book->book_image != null)
            <img src="images/books/{{ $book->book_image }}" class="img-thumbnail p-0 mb-4 border-0 rounded-0">
            @else
            <img src="image/library.png" class="img-thumbnail p-0 mb-4 border-0 rounded-0">
            @endif
        </div>
        <div class="col-md-8">
            <div class="card-body pt-0">
                <h1 class="card-title">{{ $book->book_title }}</h1>
                <div class="card-text">
                    <b>@lang('main.book.rating'): </b>
                    <div class="star-ratings d-inline-block">
                        <div class="fill-ratings" style="width: {{ optional($book->agvReview->first())->star * 20 }}%;">
                            <span>★★★★★</span>
                        </div>
                        <div class="empty-ratings">
                            <span>★★★★★</span>
                        </div>
                    </div>
                    <div class="mt-1"><b>@lang('main.book.view'):</b> {{ $book->view }}</div>
                    <div class="mt-1"><b>@lang('main.book.category'):</b> <a href="{{ route('category', $book->category->cate_id ) }}">{{ $book->category->cate_name }}</a></div>
                    <div class="mt-1"><b>@lang('main.book.author'):</b> <a href="{{ route('authors.detail', $book->author->author_id ) }}">{{ $book->author->author_name }}</a>
                    </div>
                    @if ($book->quantity == 0)
                        <div class="mt-1 text-danger">@lang('main.book.not_enough_quantity')</div>
                    @endif
                    <div class="mt-1"><b>@lang('main.publishers'):</b> <a href="{{ route('publisher.detail', $book->publisher->pub_id) }}">{{ $book->publisher->pub_name }}</a></div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <button type="button" data-id="{{ $book->book_id }}" class="likebtn btn btn-block btn-outline-dark">
                            @if(!$liked)
                                <i class="fa fa-thumbs-up" aria-hidden="true"></i> @lang('main.book.like_book')
                            @else
                                <i class="fa fa-thumbs-down" aria-hidden="true"></i> @lang('main.book.unlike_book')
                            @endif
                        </button>
                    </div>
                    <div class="col-md-6 mb-2">
                        <button type="button" class="btn btn-block btn-dark" data-toggle="modal"
                            data-target="#borrowForm" @if ($book->quantity == 0) disabled @endif>
                            <i class="fa fa-hourglass-start" aria-hidden="true"></i> @lang('main.book.borrow_book')
                        </button>
                    </div>
                </div>
                <p class="card-text pt-3">{!! nl2br(e($book->book_desc)) !!}</p>
            </div>
        </div>
    </div>
    <h3 class="heading mt-4 mb-3">@lang('main.book.review')</h3>
        @if (Auth::check())
            <form action="{{ route('reviews.store') }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="book_id" value="{{ $book->book_id }}">
                <input type="hidden" name="user_id" value="{{ Auth::user()->user_id }}">
                <div class="form-group">
                    <label for="msg">@lang('main.book.review_message')</label>
                    <textarea name="content" cols="12" class="form-control" id="msg"></textarea>
                </div>
                <div class="form-group">
                    <label for="msg">@lang('main.book.review_rating')</label>
                    <select name="rate" class="form-control" id="msg" required>
                        <option value="">@lang('main.book.choose_rating')</option>
                    @foreach(config('app.rating') as $key => $star)
                        <option value=" {{ $key }}">{{ $star }}</option>
                    @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-outline-dark">@lang('main.book.send_review')</button>
                </div>
                @if (count($errors) > 0)
                    @foreach ($errors->all() as $error)
                        <li class="alert alert-danger">{{ $error }}</li>
                    @endforeach
                @endif
            </form>
        @else 
            <p class="alert alert-danger">@lang('main.book.notLogin')</p>
        @endif

        @if (!empty($reviews))
        <div class="mt-3">
            @foreach($reviews as $review)
            <div class="card mb-3 p-2">
                <div class=" row no-gutters">
                    <div class="col-md-1 p-2">
                        @if ($review->user->avatar == null)
                            <img src="image/default_avatar.jpg" class="rounded-circle img-thumbnail" alt="">
                        @else 
                            <img src="images/users/{{ $review->user->avatar }}" class="rounded-circle img-thumbnail" alt="">
                        @endif
                    </div>
                    <div class="col-md-11">
                      <div class="card-body">
                        <h5 class="card-title mb-1"><b>{{ $review->user->fullname }}</b></h5>
                        <div class="card-text">
                            <div class="text-warning">{{ config('app.rating.' . $review->rate) }}</div>
                            <div class="mt-2">{{ $review->content }}</div>
                        </div>
                        <p class="card-text text-muted float-right"><small>{{ $review->updated_at }}</small></p>
                      </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    <hr class="featurette-divider">
</div>
<!-- form borror -->
<div class="modal fade" id="borrowForm" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="borrowFormLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="borrowFormLabel">@lang('main.book.request_borrow')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('book.borrow', $book->book_id) }}" method="POST">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label>@lang('main.book.name')</label>
                        <input type="text" class="form-control" value="{{ $book->book_title }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>@lang('main.book.borrow.date_borrow')</label>
                        <input type="datetime-local" name="borrow_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>@lang('main.book.borrow.date_return')</label>
                        <input type="datetime-local" name="return_date" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-dismiss="modal">@lang('main.close')</button>
                    @if (!Auth::check())
                    <a href="{{ route('login') }}"><button type="button"
                            class="btn btn-dark">@lang('main.login')</button></a>
                    @else
                    <button type="submit" class="btn btn-dark">@lang('main.book.request_borrow')</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
