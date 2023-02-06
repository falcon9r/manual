@extends('base.app')

@section('title')
    <title>Dashboard</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
    <div class="profile">
        <div class="title">
            <h2>Credentials</h2>
            <button type="submit" form="credentials" class="save btn" id="save-btn">Save changes</button>
        </div>
        <div class="container">
            <div class="notification">
                <p>You can change the credentials through this form.
                </p>
                <p>if the credenitals are lost, please contact the website manager.</p>
            </div>

            <div class="body-style">

                <div class="item">
                    <h4>Credentials</h4>
                    <h4>Value</h4>
                </div>
                <form action="{{ route('saveProfile') }}" id="credentials" method="post">
                    @csrf
                    <div class="item">
                        <h4>Username (Email)</h4>
                        <input type="email" name="email" id="email" value="{{ $userData->email }}"
                            placeholder="Enter username (email)" autocomplete="off" class="input">
                    </div>

                    <div class="item">
                        <h4>Password</h4>
                        <input type="password" name="password" id="password" value="previouspassword"
                            placeholder="Enter password" autocomplete="off" class="input">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        @if (session('success'))
            toastr.info("The values were saved successfully!");
        @endif
        @if(session('error'))
            toastr.error("There was an error saving the values!");
        @endif
    </script>
@endsection
