<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/updatepass.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="header">
        <div class="header-logo">
            <img src="{{ asset('img/logo.png') }}" alt="Bukit Asam">
        </div>
        <div class="profile d-flex align-items-center">
            <div class="profile-wrap">
                <h4 class="profile-name mb-0">{{ Auth::user()->name }}</h4>
            </div>
            <div class="profile-wrap">
                <h4 class="profile-dash">-</h4>
            </div>
            <a href="{{ route('logout') }}" class="profile-logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
    
        
    </div>
    <main class="container">
        <div class="form-title-box">
            <label for="reset">Reset Password</label>
        </div>

        <div class="form-text">
            <h5>Please reset your Password!</h5>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('confirmpassword') }}" method="POST">
                @csrf
                <div class="main-user-info">
                    <div class="user-input-box">
                        <label for="password">New Password:</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="input-group-append">
                                <button type="button" class="input-group-text" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="eye-icon-password"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="user-input-box">
                        <label for="password_confirmation">Confirm Password:</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            <div class="input-group-append">
                                <button type="button" class="input-group-text" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye" id="eye-icon-password_confirmation"></i>
                                </button>
                            </div>
                        </div>
                    </div>                    

                    <div class="form-submit-btn">
                        <button type="submit" class="btn btn-danger">Update Password</button>
                    </div>
                </form>
            </div>
        </main>

        <script>
            function togglePassword(inputId) {
                const passwordField = document.getElementById(inputId);
                const eyeIcon = document.getElementById(`eye-icon-${inputId}`); 
                if (passwordField.type === "password") {
                    passwordField.type = "text";
                    eyeIcon.classList.remove("fa-eye");
                    eyeIcon.classList.add("fa-eye-slash");
                } else {
                    passwordField.type = "password";
                    eyeIcon.classList.remove("fa-eye-slash");
                    eyeIcon.classList.add("fa-eye");
                }
            }
        </script>
    </body>
</html>
