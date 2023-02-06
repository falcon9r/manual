<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wilke - Login</title>
    {{-- my Css --}}
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="cont">
        <div hidden class="bgCont">
            <img class="bg" src="{{ asset('images/bg.jpg') }}" alt="background">
        </div>
        <div hidden role="hidden" class="blur">
        </div>
        <div class="wrapper">
            <div class="direct-cont">
                <div class="welcome">
                    <a href="">
                        <img src="{{ asset('images/logo.png') }}" class="logo" alt="wilke logo">
                    </a>
                    <p>Welcome to Wilke manuals!<br>Loging in ...</p>
                </div>


                <div role="hidden" class="credentials">
                    <span>Enter Credentials</span>
                </div>


                <form action="{{ route('login') }}" method="post">
                    @csrf
                    <div>
                        <input type="email" name="email" id="email"
                            @error('email') placeholder="{{ $message }}" @enderror placeholder="Your Email"
                            autocomplete="off"
                            class="minput  gray focus:dark-gray @error('email') border-red @enderror"
                            value="{{ old('email') }}">
                    </div>

                    <div>
                        <input type="password" name="password" id="password"
                            @error('password') placeholder="{{ $message }}" @enderror placeholder="Your Password"
                            autocomplete="off"
                            class="minput gray focus:dark-gray @error('password') border-red @enderror">
                    </div>

                    <div>
                        <div class="remember">
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember">Remember me</label>
                        </div>
                    </div>

                    <div>
                        <button type="submit">
                            <span>Login</span>
                        </button>
                    </div>
                </form>
                @if (session('status'))
                    <div class="error-box">
                        <div class="w-full max-w-md bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                            role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('status') }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
