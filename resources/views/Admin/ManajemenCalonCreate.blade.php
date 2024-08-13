<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Calon</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
<body>
    @include('partials.sidebar')

    <section class="content">
        <div class="subheader">
            <h3 class="subheader-title">Create Candidate</h3>
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

        <form method="POST" action="{{ route('calons.store') }}" enctype="multipart/form-data" onsubmit="return confirmSubmit();">
            @csrf

            <div class="form-group">
                <label for="type">Type:</label>
                <select name="type" id="type" class="form-control" required>
                    <option value="calon">Calon Pusat</option>
                    <option value="calondaerahjakarta">Calon Daerah Jakarta</option>
                    <option value="calondaerahlampung">Calon Daerah Lampung</option>
                    <option value="calondaerahpalembang">Calon Daerah Palembang</option>
                    <option value="calondaerahpadang">Calon Daerah Padang</option>
                    <option value="calondaerahtanjungenim">Calon Daerah Tanjung Enim</option>
                </select>
            </div>

            <div class="form-group">
                <label for="idketua">Ketua:</label>
                <select name="idketua" id="idketua" class="form-control" required>
                    <option value="">Select Ketua</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" data-idcabang="{{ $user->idcabang }}" data-pusat="{{ $user->pusat }}">{{ $user->name }} - {{ $user->cabang->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="wajahketua">Photo Ketua:</label>
                <input type="file" class="form-control-file" id="wajahketua" name="wajahketua" required>
                <img class="image-preview" style="display: none; max-width: 100px; margin-top: 10px;">
            </div>

            <div class="form-group">
                <label for="idsekretaris">Sekretaris:</label>
                <select name="idsekretaris" id="idsekretaris" class="form-control" required>
                    <option value="">Select Sekretaris</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" data-idcabang="{{ $user->idcabang }}" data-pusat="{{ $user->pusat }}">{{ $user->name }} - {{ $user->cabang->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="wajahsekretaris">Photo Sekretaris:</label>
                <input type="file" class="form-control-file" id="wajahsekretaris" name="wajahsekretaris" required>
                <img class="image-preview" style="display: none; max-width: 100px; margin-top: 10px;">
            </div>

            <div class="form-group">
                <label for="idbendahara">Bendahara:</label>
                <select name="idbendahara" id="idbendahara" class="form-control" required>
                    <option value="">Select Bendahara</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" data-idcabang="{{ $user->idcabang }}" data-pusat="{{ $user->pusat }}">{{ $user->name }} - {{ $user->cabang->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="wajahbendahara">Photo Bendahara:</label>
                <input type="file" class="form-control-file" id="wajahbendahara" name="wajahbendahara" required>
                <img class="image-preview" style="display: none; max-width: 100px; margin-top: 10px;">
            </div>

            <div class="form-group">
                <label for="visi">Visi:</label>
                <textarea class="form-control" id="visi" name="visi" rows="3" required></textarea>
            </div>

            <div class="form-group">
                <label for="misi">Misi:</label>
                <textarea class="form-control" id="misi" name="misi" rows="3" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </section>
    
    <script>
        $(document).ready(function() {
            function filterOptions() {
                var selectedType = $('#type').val();
                var isPusat = selectedType === 'calon';

                $('#idketua, #idsekretaris, #idbendahara').each(function() {
                    var $select = $(this);
                    $select.find('option').each(function() {
                        var $option = $(this);
                        var showOption = true;

                        if (isPusat && $option.data('pusat') !== 'yes') {
                            showOption = false;
                        } else if (!isPusat) {
                            var idcabang = $option.data('idcabang');
                            var hideOption = true;

                            switch (selectedType) {
                                case 'calondaerahjakarta':
                                    if (idcabang === 1) hideOption = false;
                                    break;
                                case 'calondaerahlampung':
                                    if (idcabang === 2) hideOption = false;
                                    break;
                                case 'calondaerahpalembang':
                                    if (idcabang === 3) hideOption = false;
                                    break;
                                case 'calondaerahpadang':
                                    if (idcabang === 4) hideOption = false;
                                    break;
                                case 'calondaerahtanjungenim':
                                    if (idcabang === 5) hideOption = false;
                                    break;
                            }

                            if (hideOption) {
                                showOption = false;
                            }
                        }

                        $option.toggle(showOption);
                    });
                });
            }

            $('#type').on('change', filterOptions);
            filterOptions();

            $('#idketua, #idsekretaris, #idbendahara').on('change', function() {
                var selectedValue = $(this).val();
                $('#idketua, #idsekretaris, #idbendahara').not(this).find('option').each(function() {
                    if ($(this).val() === selectedValue) {
                        $(this).remove();
                    }
                });
            });

            $('#wajahketua, #wajahsekretaris, #wajahbendahara').change(function() {
                var input = this;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $(input).closest('.form-group').find('.image-preview').attr('src', e.target.result).show();
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            });
        });

        function confirmSubmit() {
            return confirm('Are you sure you want to create this candidate?');
        }
    </script>
</body>
</html>
