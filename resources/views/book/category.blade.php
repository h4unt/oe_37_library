@extends('layouts.app')
@section('title', $title)
@section('content')
<div class="container mt-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">@lang('main.home')</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-md-3">
            <ul class="list-group list-group-flush">
            @foreach ($allCategory as $category)
                <li class="list-group-item"><a href="{{ route('category', $category->cate_id) }}"><b>{{ $category->cate_name }}</b></a></li>
                @foreach ($category->children as $subCategory)
                    <li class="list-group-item ml-2"><i class="fa fa-chevron-right" aria-hidden="true"></i> <a href="{{ route('category', $subCategory->cate_id) }}">{{ $subCategory->cate_name }}</a></li>
                @endforeach
            @endforeach
            </ul>           
        </div>
        <div class="col-md-9 mt-3">
            @if(count($books) > 0)
                <div class="row">
                @foreach($books as $book)
                    @include('subviews.book-item', [$book, 'width' => 12])
                @endforeach
                </div>
                {{ $books->links() }}
            @else 
                <div class="alert alert-info text-center">@lang('main.no_result')</div>
            @endif
        </div>

    </div>
    <hr class="featurette-divider">
</div>

@endsection
