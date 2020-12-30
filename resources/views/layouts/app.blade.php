<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title')</title>
  <link href="{{ mix('css/app.css') }}" rel="stylesheet">
  <link href="{{ mix('css/site.css') }}" rel="stylesheet">
  <link href="{{ mix('css/notifications.css') }}" rel="stylesheet">
  <base href="{{ asset('') }}">
</head>
<body class="bg-white">
  <header>
    <nav class="navbar navbar-expand-md navbar-light fixed-top bg-white">
      <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">HVBook</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
          aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
              <a class="nav-link" href="{{ route('home') }}">@lang('main.home')</a>
            </li>
            <li class="nav-item droplist">
              <a class="nav-link" href="{{ route('category.all') }}">@lang('main.categories')</a>
              <ul class="droplist-container mt-3">
                <div class="container d-block">
                  <div class="row mt-5 mb-4 ml-0 mr-0 pd-0">
                    @foreach ($allCategory as $category)
                    <div class="col-md-4 p-0">
                      <h2 class="droplist-title"><a href="{{ route('category', $category->cate_id) }}">{{ $category->cate_name }}</a>
                      </h2>
                      <ul class="droplist-list">
                        @foreach ($category->children as $subCategory)
                        <li><a href="{{ route('category', $subCategory->cate_id) }}">{{ $subCategory->cate_name }}</a></li>
                        @endforeach
                      </ul>
                    </div>
                    @endforeach
                  </div>
                </div>
              </ul>
            </li>
            @if (!Auth::check())
            <li class="nav-item">
              <a class="nav-link" href="{{ route('login') }}">@lang('main.login')</a>
            </li>
            @else
            <li class="nav-item dropdown p-2" id="dropdown-notifications">
              <a href="#notifications-panel" id="notiDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i data-count="{{ count(Auth::user()->unreadNotifications) }}" class="fas fa-bell notification-icon"></i>
              </a>
              <div class="dropdown-menu noti-content" aria-labelledby="notiDropdown">
              <div class="dropdown-header">@lang('main.notification.title')
                <span class="float-right"><a href="#mark-as-read" id="mark-as-read">@lang('main.notification.view_all')</a></span>
                </div>
                <div id="notiContent">
                @foreach (Auth::user()->unreadNotifications as $notification)
                  <a class="dropdown-item @if($notification->read_at == null) bg-warning @endif" href="#"><strong>{{ $notification->data['title'] }}</strong>
                  <br/>
                  <span class="text-muted text">{{ $notification->data['content'] }}</span>
                  <!--<small class="float-right">{{ $notification->created_at }}</small>-->
                  </a>
                @endforeach
                </div>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                @lang('main.hello'), <b>{{ Auth::user()->username }}</b>
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="{{ route('user.my-account') }}">@lang('main.my_account')</a>
                @if(Auth::user()->can('accessAdmin', 1)) <a class="dropdown-item text-danger"
                  href="{{ route('admin.index') }}">@lang('admin.home')</a> @endif
                  <a class="dropdown-item" href="{{ route('borrows.history', Auth::user()->user_id) }}">@lang('main.borrow_history')</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" id="logout">@lang('auth.logout')</a>
              </div>
            </li>
            @endif
          </ul>
        </div>
      </div>
    </nav>
  </header>
  <main role="main">
    @yield('content')
    <footer class="container">
      <p>&copy; 2020 &middot; <a href="{{ route('locale','vi') }}" class="text-dark">@lang('main.locate_vi')</a>
        &middot; <a href="{{ route('locale','en') }}" class="text-dark">@lang('main.locate_en')</a></p>
    </footer>
  </main>
  <script src="{{ mix('js/app.js') }}"></script>
  <script src="https://js.pusher.com/4.4/pusher.min.js"></script>
  <script src="{{ mix('js/site.js') }}"></script>
</body>

</html>
