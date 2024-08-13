<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Candidate Daerah</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
    @include('partials.sidebar')

    <section class="content">
        <div class="subheader">
            <h3 class="subheader-title">Edit Candidate Daerah</h3>
        </div>

        <a class="back" href="{{ route('calons.index') }}">< Back</a>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="editCandidateForm" action="{{ route('calonsdaerah.update', $calondaerah->idcaldar) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Ketua</th>
                        <th>Sekretaris</th>
                        <th>Bendahara</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="file" class="form-control-file" id="wajahketua" name="wajahketua" onchange="previewImage(this, 'previewKetua')">
                            <div id="previewKetua" class="mt-2">
                                @if ($calondaerah->wajahketua)
                                    <img src="{{ asset('storage/' . $calondaerah->wajahketua) }}" class="img-thumbnail" style="max-width: 200px;">
                                @else
                                    <p>No image uploaded.</p>
                                @endif
                            </div>
                        </td>
                        <td>
                            <input type="file" class="form-control-file" id="wajahsekretaris" name="wajahsekretaris" onchange="previewImage(this, 'previewSekretaris')">
                            <div id="previewSekretaris" class="mt-2">
                                @if ($calondaerah->wajahsekretaris)
                                    <img src="{{ asset('storage/' . $calondaerah->wajahsekretaris) }}" class="img-thumbnail" style="max-width: 200px;">
                                @else
                                    <p>No image uploaded.</p>
                                @endif
                            </div>
                        </td>
                        <td>
                            <input type="file" class="form-control-file" id="wajahbendahara" name="wajahbendahara" onchange="previewImage(this, 'previewBendahara')">
                            <div id="previewBendahara" class="mt-2">
                                @if ($calondaerah->wajahbendahara)
                                    <img src="{{ asset('storage/' . $calondaerah->wajahbendahara) }}" class="img-thumbnail" style="max-width: 200px;">
                                @else
                                    <p>No image uploaded.</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="form-group">
                <label for="visi">Visi:</label>
                <textarea class="form-control" id="visi" name="visi" rows="3" required>{{ $calondaerah->visi }}</textarea>
            </div>

            <div class="form-group">
                <label for="misi">Misi:</label>
                <textarea class="form-control" id="misi" name="misi" rows="3" required>{{ $calondaerah->misi }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary" onclick="confirmEdit(event)">Update Candidate Daerah</button>
        </form>
    </section>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function previewImage(input, previewId) {
            var preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'mt-2 img-thumbnail';
                    img.style.maxWidth = '200px';
                    preview.innerHTML = '';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.innerHTML = '<p>No image uploaded.</p>';
            }
        }

        function confirmEdit(event) {
            event.preventDefault();
            if (confirm('Are you sure you want to update this candidate?')) {
                document.getElementById('editCandidateForm').submit();
            }
        }
    </script>
</body>
</html>
