<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{asset('css/style.css')}}"rel="stylesheet">
</head>

<body>
    <header class="header fixed">
        <div class="header-logo">
            <img src="{{asset('./img/logo.png')}}" alt="Bukit Asam">
        </div>
        <div class="profile">
            <div class="profile-wrap">
                <h4 class="profile-name">Administrator</h4>
            </div>
        </div>
    </header>
    <main class="wrapper">
        <aside class="aside">
            <nav class="nav">
                <a href="{{ route('admin') }}" class="nav-link active">Dashboard</a>
                <a href="{{ route('admin.users.index') }}" class="nav-link">Management User</a>
                <a href="{{ route('calons.index') }}" class="nav-link">Management Calon</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link logout-button">Logout</button>
                </form>
            </nav>
        </aside>
</body>

</html>
