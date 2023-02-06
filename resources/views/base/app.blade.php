<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- Material CDN --}}
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@48,400,0,0" />
    {{-- toaster Css --}}
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    {{-- base Css --}}
    <link rel="stylesheet" href="{{ asset('css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('css/offline/ckeditor.css') }}" type="text/css">
    {{-- my Css --}}
    @yield('css')
    {{-- my Title --}}
    @yield('title')

</head>
<body class="@if ($darkTheme) dark-theme-variables @endif">
    <div class="base-cont" id="baseCont">

        {{-- left side --}}

        <aside>
            <div class="top">
                <div class="logo">
                    <a href="/">
                        <h2>Wilke <span class="dark-variant">Technology</span></h2>
                        <img src="{{ asset('images/logo.png') }}" alt="wilke logo">
                    </a>
                </div>

            </div>

            <div class="sidebar">
                <a href="{{ route('dashboard') }}" class="@if ($dashActive) active @endif">
                    <span class="material-symbols-sharp">grid_view</span>
                    <h3>Chapters</h3>
                </a>
                <a href="{{ route('templates') }}" class="@if ($tempActive) active @endif">
                    <span class="material-symbols-sharp">newspaper</span>
                    <h3>Templates</h3>
                </a>
                <a href="{{ route('styling') }}" class="@if ($styleActive) active @endif">
                    <span class="material-symbols-sharp">style</span>
                    <h3>Styling</h3>
                </a>
                <a href="{{ route('welcome') }}" class="@if ($welcomeActive) active @endif">
                    <span class="material-symbols-sharp">waving_hand</span>
                    <h3>Welcome page</h3>
                </a>
                <a href="{{ route('download') }}" class="@if ($downloadActive) active @endif">
                    <span class="material-symbols-sharp">cloud_download</span>
                    <h3>Download Html</h3>
                </a>
                <form action="{{ route('logout') }}" method="post" id="my_form" class="inline">
                    @csrf
                    <a href="javascript:document.getElementById('my_form').submit();">
                        <span class="material-symbols-sharp">logout</span>
                        <h3>Logout</h3>
                    </a>
                </form>

            </div>
        </aside>

        {{-- main contents --}}

        <main>
            @yield('content')
        </main>

        {{-- right and top side --}}

        <div class="base-right">
            <div class="top sticky-header">
                <button id="menu-btn">
                    <span class="material-symbols-sharp">menu</span>
                </button>
                <div class="theme-toggle">
                    <span
                        class="material-symbols-sharp @if (!$darkTheme) active @endif">light_mode</span>
                    <span
                        class="material-symbols-sharp @if ($darkTheme) active @endif">dark_mode</span>
                </div>
                <div class="profile" onclick="window.location.href='{{ route ('profile')}}'">
                    <div class="info">
                        <p>Hey, <b>Admin</b></p>
                        <small class="text-muted">Wilke Manuals</small>
                    </div>
                    <div class="profile-photo">
                        <img src="{{ asset('images/profile.jpg') }}">
                    </div>
                </div>
            </div>

            <div class="recent-updates">
                <h2>Recent updates</h2>
                <div class="updates">

                    @foreach($recents as $rec)
                    <div class="update">
                        <div class="profile-photo">
                            <img src="{{ asset('images/profile.jpg') }}">
                        </div>
                        <div class="message">
                            <p><b>Admin</b> {{explode(" ||| ",$rec)[0]}}</p>
                            <small class="text-muted">
                                {{explode(" ||| ",$rec)[1]}}
                            </small>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>

    @yield('modal')
    @yield('warning')

    {{-- Scripts --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('js/menu.js') }}"></script>
    <script src="{{ asset('js/theme.js') }}"></script>
    <script>
        var csrf = "{{ csrf_token() }}";
        toastr.options = {
            "debug": false,
            "positionClass": "toast-top-center",
            "onclick": null,
            "fadeIn": 300,
            "fadeOut": 1000,
            "timeOut": 3500
        }
    </script>
    @yield('script')
</body>
</html>
