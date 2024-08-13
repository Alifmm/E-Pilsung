<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calon Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
</head>

<body>
    @include('partials.sidebar')
    <section class="content">
        <div class="subheader">
            <h3 class="subheader-title">Management Calon</h3>
        </div>

        <div class="section-title">
            <h5>Calon Pusat</h5>
            <a href="{{ route('calons.create') }}" class="btn btn-primary">Create New Candidate</a>
        </div>
        <table class="table">
            <thead class="text-center">
                <tr>
                    <th class="wider-calon">Nama Ketua (Cabang)</th>
                    <th class="wider-calon">Nama Sekretaris (Cabang)</th>
                    <th class="wider-calon">Nama Bendahara (Cabang)</th>
                    <th class="wider">Actions</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($calons as $calon)
                <tr>
                    <td>{{ $calon->ketua ? $calon->ketua->name : 'N/A' }} ({{ $calon->ketua_cabang_name }})</td>
                    <td>{{ $calon->sekretaris ? $calon->sekretaris->name : 'N/A' }} ({{ $calon->sekretaris_cabang_name }})</td>
                    <td>{{ $calon->bendahara ? $calon->bendahara->name : 'N/A' }} ({{ $calon->bendahara_cabang_name }})</td>
                    <td>
                        <button class="fas fa-eye" onclick="showDetails('{{ $calon->idcalon }}', '{{ $calon->ketua ? $calon->ketua->name : 'N/A' }}', '{{ $calon->wajahketua }}', '{{ $calon->sekretaris ? $calon->sekretaris->name : 'N/A' }}', '{{ $calon->wajahsekretaris }}', '{{ $calon->bendahara ? $calon->bendahara->name : 'N/A' }}', '{{ $calon->wajahbendahara }}', '{{ $calon->visi }}', '{{ $calon->misi }}')">
                        </button>
                        <a href="{{ route('calons.edit', $calon->idcalon) }}" class="fas fa-pencil-alt"></a>
                        <form action="{{ route('calons.destroy', $calon->idcalon) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="fas fa-trash-alt" style="background: none; border: none; cursor: pointer;"></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        

        <div class="section-title">
            <h5>Calon Daerah</h5>
            <div class="form-group">
                <label for="region-select">Select Region:</label>
                <select id="region-select" class="form-control" onchange="filterTable()">
                    <option value="All">All</option>
                    <option value="jakarta">Jakarta</option>
                    <option value="lampung">Lampung</option>
                    <option value="palembang">Palembang</option>
                    <option value="padang">Padang</option>
                    <option value="tanjung enim">Tanjung Enim</option>
                </select>
            </div>
        </div>
        <table class="table" id="calondaerah-table">
            <thead class="text-center">
                <tr>
                    <th class="wider">Cabang</th>
                    <th class="wider">Nama Ketua</th>
                    <th class="wider">Nama Sekretaris</th>
                    <th class="wider">Nama Bendahara</th>
                    <th class="wider">Actions</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($calondaerahs as $calondaerah)
                <tr>
                    <td>{{ $calondaerah->cabang }}</td>
                    <td>{{ $calondaerah->ketua ? $calondaerah->ketua->name : 'N/A' }}</td>
                    <td>{{ $calondaerah->sekretaris ? $calondaerah->sekretaris->name : 'N/A' }}</td>
                    <td>{{ $calondaerah->bendahara ? $calondaerah->bendahara->name : 'N/A' }}</td>
                    <td>
                        <button class="fas fa-eye" onclick="showDetails('{{ $calondaerah->idcaldar }}', '{{ $calondaerah->ketua ? $calondaerah->ketua->name : 'N/A' }}', '{{ $calondaerah->wajahketua }}', '{{ $calondaerah->sekretaris ? $calondaerah->sekretaris->name : 'N/A' }}', '{{ $calondaerah->wajahsekretaris }}', '{{ $calondaerah->bendahara ? $calondaerah->bendahara->name : 'N/A' }}', '{{ $calondaerah->wajahbendahara }}', '{{ $calondaerah->visi }}', '{{ $calondaerah->misi }}')">
                        </button>
                        <a href="{{ route('calonsdaerah.edit', $calondaerah->idcaldar) }}" class="fas fa-pencil-alt"></a>
                        <form action="{{ route('calonsdaerah.destroy', $calondaerah->idcaldar) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete();">
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

    <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Candidate Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <h6><strong>Nama Ketua</strong></h6>
                            <p id="ketuaName"></p>
                            <img id="ketuaImage" src="" alt="Wajah Ketua" class="img-small">
                        </div>
                        <div class="col-md-4">
                            <h6><strong>Nama Sekretaris</strong></h6>
                            <p id="sekretarisName"></p>
                            <img id="sekretarisImage" src="" alt="Wajah Sekretaris" class="img-small">
                        </div>
                        <div class="col-md-4">
                            <h6><strong>Nama  Bendahara</strong></h6>
                            <p id="bendaharaName"></p>
                            <img id="bendaharaImage" src="" alt="Wajah Bendahara" class="img-small">
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h6><strong>Visi</strong></h6>
                            <p id="visi"></p>
                            <h6><strong>Misi</strong></h6>
                            <p id="misi"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterTable() {
            var select = document.getElementById('region-select');
            var table = document.getElementById('calondaerah-table');
            var rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            var selectedRegion = select.value.toLowerCase();

            for (var i = 0; i < rows.length; i++) {
                var cabangCell = rows[i].getElementsByTagName('td')[0];
                var cabangText = cabangCell.textContent.toLowerCase();

                if (selectedRegion === 'all' || cabangText === selectedRegion) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        }

        function showDetails(id, ketuaName, ketuaImage, sekretarisName, sekretarisImage, bendaharaName, bendaharaImage, visi, misi) {
            document.getElementById('ketuaName').textContent = ketuaName || 'N/A';
            document.getElementById('ketuaImage').src = ketuaImage ? `{{ asset('storage') }}/${ketuaImage}` : '';
            document.getElementById('sekretarisName').textContent = sekretarisName || 'N/A';
            document.getElementById('sekretarisImage').src = sekretarisImage ? `{{ asset('storage') }}/${sekretarisImage}` : '';
            document.getElementById('bendaharaName').textContent = bendaharaName || 'N/A';
            document.getElementById('bendaharaImage').src = bendaharaImage ? `{{ asset('storage') }}/${bendaharaImage}` : '';
            document.getElementById('visi').textContent = visi || 'N/A';
            document.getElementById('misi').textContent = misi || 'N/A';

            $('#detailsModal').modal('show');
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
</body>

</html>
