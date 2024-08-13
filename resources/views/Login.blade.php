<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="container">
        <h1 class="form-title">E-PILSUNG PT Bukit Asam</h1>
        <h5 class="form-text">Please fill in your login details below</h5>

        @if (session('error'))
            <p style="color: red;">{{ session('error') }}</p>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="main-user-info">
                <div class="user-input-box">
                    <label for="email">Email address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <p style="color: red;">{{ $message }}</p>
                    @enderror
                </div>
                <div class="user-input-box">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                    @error('password')
                        <p style="color: red;">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="form-submit-btn">
                <input type="submit" value="Sign In">
            </div>
        </form>
    </div>
</body>
</html>
