@extends('layouts.login')

@section('title')
    Employee Login
@endsection

@section('header')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/css/login.css">
@endsection

@section('content')
    <div class="wrapper">
        <div class="text-center mt-4 name">
            Product Registration System
        </div>
        <div class="error">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <form method="POST" action="{{ route('login.check') }}">
            @csrf
            <div class="form-field d-flex align-items-center mt-5">
                <span class="far fa-user"></span>
                <input type="text" name="employee_id" id="employee_id" value="{{ old('employee_id') }}"
                    placeholder="Employee ID" autocomplete="off">
            </div>
            @error('employee_id')
                <div class="text-danger">{{ $message }}</div>
            @enderror
            <div class="form-field d-flex align-items-center mt-5">
                <span class="fas fa-key"></span>
                <input type="password" name="password" id="password" value="{{ old('password') }}" placeholder="Password"
                    autocomplete="off" oninput="togglePasswordWhenInput()">
                <div class="me-3">
                    <span id="togglePassword" onclick="togglePasswordVisibility()">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
            <button class="btn mt-3">Login</button>
        </form>

    </div>

    <script>
        //eye icon
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("password");
            var togglePassword = document.getElementById("togglePassword");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                togglePassword.innerHTML = '<i class="fas fa-eye-slash"></i>';
            } else {
                passwordInput.type = "password";
                togglePassword.innerHTML = '<i class="fas fa-eye"></i>';
            }
        }

        function togglePasswordWhenInput() {
            var passwordInput = document.getElementById("password");
            var togglePassword = document.getElementById("togglePassword");

            if (passwordInput.value !== "") {
                togglePassword.style.display = "inline-block";
            } else {
                togglePassword.style.display = "none";
            }
        }

        togglePasswordWhenInput(); // Initial check when the page loads
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        //When the page is reloaded, the login input fields are disappearing
        if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
            // Reload the page if navigating back
            location.reload(true);
        }


        $(document).ready(function() {

            //alert timeout
            setTimeout(function() {
                $(".text-danger").fadeOut();
            }, 5000)
        });
    </script>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous">
    </script>
@endsection
