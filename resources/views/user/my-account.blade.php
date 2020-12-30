@extends('layouts.app')
@section('title', Auth::user()->fullname )
@section('content')

<div class="container mt-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">@lang('main.home')</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Auth::user()->username }}</li>
        </ol>
    </nav>  
    <h3 class="heading mt-5">@lang('main.my_account')</h3>
    <div class="text-center mb-5">
    @if (Auth::user()->avatar == null)
        <img src="image/default_avatar.jpg" class="rounded-circle img-thumbnail w-25" alt="">
    @else 
        <img src="images/users/{{ Auth::user()->avatar }}" class="rounded-circle img-thumbnail w-25" alt="">
    @endif    
    </div>
    <div class="table-responsive">
    <table class="table table-bordered">
        <tbody>
        <tr>
            <th scope="row">@lang('main.account.fullname')</th>
            <td>{{ Auth::user()->fullname }}</td>
            </tr>
            <tr>
            <th scope="row">@lang('main.account.email')</th>
            <td>{{ Auth::user()->email }}</td>
            </tr>
            <tr>
            <th scope="row">@lang('main.account.birthday')</th>
            <td>{{ Auth::user()->birthday }}</td>
            </tr>
            <th scope="row">@lang('main.account.phone')</th>
            <td>{{ Auth::user()->phone }}</td>
            </tr>
        </tbody>
    </table>
        
    </div>
</div>
<hr class="featurette-divider">

@endsection
