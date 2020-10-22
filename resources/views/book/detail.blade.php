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
        @if(file_exists( public_path().'/images/books/'.$book->book_image ) && $book->book_image != null)
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
                        <div class="fill-ratings" style="width: 85%;">
                            <span>★★★★★</span>
                        </div>
                        <div class="empty-ratings">
                            <span>★★★★★</span>
                        </div>
                    </div>
                    <div class="mt-1"><b>@lang('main.book.view'):</b> {{ $book->view }}</div>
                    <div class="mt-1"><b>@lang('main.book.category'):</b> <a href="#">{{ $book->category->cate_name }}</a></div>
                    <div class="mt-1"><b>@lang('main.book.author'):</b> <a href="#">{{ $book->author->author_name }}</a></div>
                    @if($book->quantity == 0)<div class="mt-1 text-danger">@lang('main.book.not_enough_quantity')</div>@endif
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <a href="#" class="btn btn-block btn-outline-dark">
                            <i class="fa fa-thumbs-up" aria-hidden="true"></i> @lang('main.book.like_book')
                        </a>
                    </div>
                    <div class="col-md-6 mb-2">
                        <button type="button" class="btn btn-block btn-dark" data-toggle="modal" data-target="#borrowForm" @if($book->quantity == 0) disabled @endif>
                            <i class="fa fa-hourglass-start" aria-hidden="true"></i> @lang('main.book.borrow_book')
                        </button>
                    </div>
                </div>
                <p class="card-text pt-3">{!! nl2br(e($book->book_desc)) !!}</p>
            </div>
        </div>
    </div>
    <h3 class="heading mt-4">@lang('main.book.review')</h3>
    <form>
        <div class="form-group">
            <label for="msg">@lang('main.book.review_message')</label>
            <textarea cols="12" class="form-control" id="msg"></textarea>
        </div>
        <div class="form-group">
            <label for="msg">@lang('main.book.review_rating')</label>
            <select class="form-control" id="msg" required>
                <option value="">@lang('main.book.choose_rating')</option>
                @foreach(config('app.rating') as $key => $star)
                <option value=" {{ $key }}">{{ $star }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-outline-dark">@lang('main.book.send_review')</button>
        </div>
    </form>
    <hr class="featurette-divider">
</div>
<!-- form borror -->
<div class="modal fade" id="borrowForm" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="borrowFormLabel" aria-hidden="true">
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
            <a href="{{ route('login') }}"><button type="button" class="btn btn-dark">@lang('main.login')</button></a>
        @else
            <button type="submit" class="btn btn-dark">@lang('main.book.request_borrow')</button>
        @endif
        </div>
    </form>
    </div>
  </div>
</div>

@endsection
