<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fontawesome.com/icons/" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>

<body>
    @include('partials.sidebar')

    <section class="content">
        <div class="subheader">
            <h3 class="subheader-title">Add User</h3>
        </div>

        <a class="back" href="{{ route('admin.users.index') }}">< Back</a>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.store') }}" method="POST" onsubmit="return confirmCreate();">
            @csrf

            <div class="form-group">
                <label for="NIK">NIK:</label>
                <input type="number" class="form-control" id="NIK" name="NIK" value="{{ old('NIK') }}" placeholder="Enter NIK" required>
            </div>

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter Name" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Enter Email" required>
            </div>

            <div class="form-group">
                <label for="idcabang">Nama Cabang:</label>
                <select class="form-control" id="idcabang" name="idcabang" required>
                    <option value="">Select Cabang</option>
                    @foreach ($cabangs as $cabang)
                        <option value="{{ $cabang->idcabang }}" {{ old('idcabang') == $cabang->idcabang ? 'selected' : '' }}>
                            {{ $cabang->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Create User</button>
        </form>
    </section>

    <script>
        function confirmCreate() {
            return confirm('Are you sure you want to create this user?');
        }
    </script>
</body>

</html>
