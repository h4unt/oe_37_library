<div class="col-md-6">
    <div class="card mb-3">
        <div class="row no-gutters">
            <div class="col-md-4">
                @if(file_exists( public_path().'/images/books/'.$book->book_image ) && $book->book_image != null)
                    <img src="images/books/{{ $book->book_image }}" class="img-thumbnail p-0 border-0 rounded-0">
                @else
                    <img src="image/library.png" class="img-thumbnail p-0 border-0 rounded-0">
                @endif
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">{{ $book->book_title }}</h5>
                    <p class="card-text">{{ str_limit($book->book_desc, 120) }}</p>
                    <div class="card-text">
                        <div class="star-ratings d-inline-block">
                            <div class="fill-ratings" style="width: 85%;">
                                <span>★★★★★</span>
                            </div>
                            <div class="empty-ratings">
                                <span>★★★★★</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6 prod-price mb-2">
                            <a href="{{ route('book.detail', $book->book_id) }}" class="btn btn-block btn-outline-dark">
                                <i class="fa fa-thumbs-up" aria-hidden="true"></i> @lang('main.book.like_book')
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('book.detail', $book->book_id) }}" class="btn btn-block btn-outline-dark">
                                <i class="fa fa-eye" aria-hidden="true"></i> @lang('main.book.view_book')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
