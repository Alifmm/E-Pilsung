<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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
            <h3 class="subheader-title">Edit User</h3>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <a class="back" href="{{ route('admin.users.index') }}">< Back</a>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" onsubmit="return confirmUpdate();">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="NIK">NIK:</label>
                <input type="number" class="form-control" id="NIK" name="NIK" value="{{ old('NIK', $user->NIK) }}" required>
            </div>

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password if you want to change">
            </div>

            <div class="form-group">
                <label for="idcabang">Nama Cabang:</label>
                <select class="form-control" id="idcabang" name="idcabang" required>
                    @foreach ($cabangs as $cabang)
                        <option value="{{ $cabang->idcabang }}"
                            {{ old('idcabang', $user->idcabang) == $cabang->idcabang ? 'selected' : '' }}>
                            {{ $cabang->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="pusat">Pusat:</label>
                <select class="form-control" id="pusat" name="pusat" required>
                    <option value="yes" {{ old('pusat', $user->pusat) == 'yes' ? 'selected' : '' }}>Yes</option>
                    <option value="no" {{ old('pusat', $user->pusat) == 'no' ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </section>

    <script>
        function confirmUpdate() {
            return confirm('Are you sure you want to update this user?');
        }
    </script>
</body>

</html>
