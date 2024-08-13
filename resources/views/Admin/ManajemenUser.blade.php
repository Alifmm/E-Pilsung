<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}" />
</head>

<body>
    @include('partials.sidebar')
    <section class="content">
        <div class="subheader">
            <h3 class="subheader-title">Management User</h3>
        </div>
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Create New User</a>
            <div class="d-flex ml-auto">
                <input type="text" id="searchInput" class="form-control mr-2 search-height" placeholder="Search by Name, NIK, or Cabang">
                <button class="btn btn-secondary" id="searchButton">Search</button>
            </div>
        </div>
        <table class="table" id="userTable">
            <thead>
                <tr>
                    <th>NIK</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Nama Cabang</th>
                    <th>Calon Pusat</th>
                    <th>Status Vote</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr>
                    <td class="user-nik">{{ $user->NIK }}</td>
                    <td class="user-name">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td class="user-cabang">{{ $user->cabang->name }}</td>
                    <td class="capitalize-text">{{ $user->pusat }}</td>
                    <td>{{ $user->status_vote }}</td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="fas fa-pencil-alt"></a>
                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirmDelete();">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="fas fa-trash-alt" style="background: none; border: none; cursor: pointer;"></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this user?');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');
            const userTable = document.getElementById('userTable').getElementsByTagName('tbody')[0];
            const rows = userTable.getElementsByTagName('tr');

            searchButton.addEventListener('click', filterTable);
            searchInput.addEventListener('keyup', function (event) {
                if (event.key === 'Enter') {
                    filterTable();
                }
            });

            function filterTable() {
                const searchValue = searchInput.value.toLowerCase();

                Array.from(rows).forEach(row => {
                    const nik = row.getElementsByClassName('user-nik')[0].textContent.toLowerCase();
                    const name = row.getElementsByClassName('user-name')[0].textContent.toLowerCase();
                    const cabang = row.getElementsByClassName('user-cabang')[0].textContent.toLowerCase();

                    const match = nik.includes(searchValue) || name.includes(searchValue) || cabang.includes(searchValue);

                    if (match) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
        });
    </script>
</body>

</html>
