<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/vote.css') }}" />
    <title>Document</title> 
</head>
<body>
    <header class="header">
        <img src="{{ asset('img/logo.png') }}" alt="Bukit Asam" class="header-logo" />
        <h2 class="header-user">{{ Auth::user()->name }}</h2>
    </header>
    <section class="banner">
        <div class="container">
            <h1 class="banner-title">E-pilsung PT Bukit Asam Tbk</h1>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="banner-wrap">
                <p class="banner-description">
                    Selamat datang di aplikasi e-Pilsung untuk pemilihan Ketua,
                    Sekretaris, dan Bendahara Perusahaan Pusat dan Cabang {{ $regionName }}. Silakan pilih
                    kandidat pilihan Anda untuk setiap posisi di bawah ini.
                </p>
            </div>
            <form id="vote-form" method="POST" action="{{ route('vote.submit') }}">
                @csrf
                <div class="box">
                    <h3 class="box-heading">Calon Pusat</h3>
                    <div class="box-content">
                        @foreach ($calons as $index => $calon)
                            <div class="vote" style="background: #ffc40d">
                                <img src="{{ asset('storage/' . $calon->wajahketua) }}" class="vote-img" />
                                <div class="vote-wrap">
                                    <h3 class="vote-title">Calon No {{ $index + 1 }}</h3>
                                    <h4 class="vote-subtitle">{{ $calon->ketua->name }}</h4>
                                </div>
                                <div class="vote-action">
                                    <button type="button" class="btn btn-white" onclick="showDetails({{ $calon->idcalon }}, 'pusat', {{ $index + 1 }}, '{{ $calon->ketua->cabang_name }}')">
                                        DETAIL
                                    </button>
                                    <button type="button" class="btn btn-primary btn-vote" data-type="pusat" data-id="{{ $calon->idcalon }}" data-name="{{ $calon->ketua->name }}" data-region="{{ $calon->ketua->cabang_name }}" onclick="selectCandidate(this)">VOTE</button>
                                </div>
                            </div>
                        @endforeach
                        @if ($calons->count() <= 1)
                            <div class="vote" style="background: #cccccc">
                                <img src="https://placehold.co/400" class="vote-img" />
                                <div class="vote-wrap">
                                    <h3 class="vote-title">Kotak Kosong</h3>
                                </div>
                                <div class="vote-action">
                                    <button type="button" class="btn btn-primary btn-vote" data-type="pusat" data-id="" data-name="Kotak Kosong" onclick="selectCandidate(this)">VOTE</button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <h3 class="box-heading">Calon Cabang {{ $regionName }}</h3>
                    <div class="box-content">
                        @foreach ($calondaerahs as $index => $calondaerah)
                            <div class="vote" style="background: #f02626">
                                <img src="{{ asset('storage/' . $calondaerah->wajahketua) }}" class="vote-img" />
                                <div class="vote-wrap">
                                    <h3 class="vote-title">Calon No {{ $index + 1 }}</h3>
                                    <h4 class="vote-subtitle">{{ $calondaerah->ketua->name }}</h4>
                                </div>
                                <div class="vote-action">
                                    <button type="button" class="btn btn-white" onclick="showDetails({{ $calondaerah->idcaldar }}, 'daerah', {{ $index + 1 }}, '{{ $regionName }}')">
                                        DETAIL
                                    </button>
                                    <button type="button" class="btn btn-primary btn-vote" data-type="daerah" data-id="{{ $calondaerah->idcaldar }}" data-name="{{ $calondaerah->ketua->name }}" data-region="{{ $regionName }}" onclick="selectCandidate(this)">VOTE</button>
                                </div>
                            </div>
                        @endforeach
                        @if ($calondaerahs->count() <= 1)
                            <div class="vote" style="background: #cccccc">
                                <img src="{{ asset('img/empty.jpg') }}" class="vote-img" />
                                <div class="vote-wrap">
                                    <h3 class="vote-title">Kotak Kosong</h3>
                                </div>
                                <div class="vote-action">
                                    <button type="button" class="btn btn-primary btn-vote" data-type="daerah" data-id="" data-name="Kotak Kosong" onclick="selectCandidate(this)">VOTE</button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <input type="hidden" name="pusat_candidate_id" id="pusat-candidate-id">
                <input type="hidden" name="daerah_candidate_id" id="daerah-candidate-id">
                <input type="hidden" id="pusat-candidate-name">
                <input type="hidden" id="daerah-candidate-name">
                <input type="hidden" id="pusat-candidate-region">
                <input type="hidden" id="daerah-candidate-region">
                <button type="submit" class="btn btn-secondary">Submit</button>
            </form>
        </div>
    </section>
    <div class="modal-container" id="modal-details">
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title" id="modal-title">Calon No 1 Pusat</h3>
                <button class="btn btn-close" id="btn-close" onclick="closeModal()">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="modal-body">
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/js/all.min.js"></script>
    <script>
        const calonData = @json($calons);
        const calondaerahData = @json($calondaerahs);

        function showDetails(candidateId, type, candidateNo, region) {
            let candidate;
            if (type === 'pusat') {
                candidate = calonData.find(calon => calon.idcalon === candidateId);
            } else if (type === 'daerah') {
                candidate = calondaerahData.find(calon => calon.idcaldar === candidateId);
            }

            const titleText = type === 'pusat' ? `Calon No ${candidateNo} Pusat` : `Calon No ${candidateNo} Cabang ${region}`;
            document.getElementById('modal-title').innerText = titleText;

            const modalBody = document.getElementById('modal-body');
            modalBody.innerHTML = `
                <div class="candidate">
                    <div class="candidate-people">
                        <div class="people">
                            <img src="{{ asset('storage') }}/${candidate.wajahketua}" class="people-img" />
                            <div class="people-wrap">
                                <h3 class="people-title">Ketua</h3>
                                <h4 class="people-subtitle">${candidate.ketua.name}${type === 'pusat' ? ' (' + candidate.ketua.cabang_name + ')' : ''}</h4>
                            </div>
                        </div>
                        <div class="people">
                            <img src="{{ asset('storage') }}/${candidate.wajahsekretaris}" class="people-img" />
                            <div class="people-wrap">
                                <h3 class="people-title">Sekretaris</h3>
                                <h4 class="people-subtitle">${candidate.sekretaris.name}${type === 'pusat' ? ' (' + candidate.sekretaris.cabang_name + ')' : ''}</h4>
                            </div>
                        </div>
                        <div class="people">
                            <img src="{{ asset('storage') }}/${candidate.wajahbendahara}" class="people-img" />
                            <div class="people-wrap">
                                <h3 class="people-title">Bendahara</h4>
                                <h4 class="people-subtitle">${candidate.bendahara.name}${type === 'pusat' ? ' (' + candidate.bendahara.cabang_name + ')' : ''}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="candidate-detail">
                        <h3 class="detail-title">Visi</h3>
                        <p class="detail-box">${candidate.visi}</p>
                        <h3 class="detail-title">Misi</h3>
                        <p class="detail-box">${candidate.misi}</p>
                    </div>
                </div>
            `;
            document.getElementById('modal-details').classList.add('modal-show');
        }

        function closeModal() {
            document.getElementById('modal-details').classList.remove('modal-show');
        }

        function selectCandidate(button) {
            const type = button.getAttribute('data-type');
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');
            const region = button.getAttribute('data-region');
            if (type === 'pusat') {
                document.getElementById('pusat-candidate-id').value = id === "null" ? "" : id;
                document.getElementById('pusat-candidate-name').value = name;
                document.getElementById('pusat-candidate-region').value = region;
                document.querySelectorAll('.btn-vote[data-type="pusat"]').forEach(btn => btn.classList.remove('selected'));
            } else if (type === 'daerah') {
                document.getElementById('daerah-candidate-id').value = id === "null" ? "" : id;
                document.getElementById('daerah-candidate-name').value = name;
                document.getElementById('daerah-candidate-region').value = region;
                document.querySelectorAll('.btn-vote[data-type="daerah"]').forEach(btn => btn.classList.remove('selected'));
            }
            button.classList.add('selected');
        }

        document.addEventListener("DOMContentLoaded", () => {
            const voteForm = document.getElementById("vote-form");
            voteForm.addEventListener("submit", function(event) {
                const pusatCandidateId = document.getElementById("pusat-candidate-id").value;
                const daerahCandidateId = document.getElementById("daerah-candidate-id").value;
                const pusatCandidateName = document.getElementById("pusat-candidate-name").value;
                const daerahCandidateName = document.getElementById("daerah-candidate-name").value;
                const pusatCandidateRegion = document.getElementById("pusat-candidate-region").value;
                const daerahCandidateRegion = document.getElementById("daerah-candidate-region").value;

                if (pusatCandidateName === "" || daerahCandidateName === "") {
                    event.preventDefault();
                    alert("Please select both candidates or choose 'Kotak Kosong' for both positions.");
                    return;
                }

                const confirmation = confirm(`Anda yakin ingin memilih calon berikut?\n\nCalon Pusat: ${pusatCandidateName || "Kotak Kosong"} \nCalon Daerah: ${daerahCandidateName || "Kotak Kosong"}`);
                if (!confirmation) {
                    event.preventDefault();
                }

                // Log the submission data for debugging
                console.log(`Pusat Candidate ID: ${pusatCandidateId}`);
                console.log(`Daerah Candidate ID: ${daerahCandidateId}`);
                console.log(`Pusat Candidate Name: ${pusatCandidateName}`);
                console.log(`Daerah Candidate Name: ${daerahCandidateName}`);
                console.log(`Pusat Candidate Region: ${pusatCandidateRegion}`);
                console.log(`Daerah Candidate Region: ${daerahCandidateRegion}`);
            });

            const voteButtons = document.querySelectorAll(".btn-white");
            const closeModalButton = document.getElementById("btn-close");

            voteButtons.forEach((button) => {
                button.addEventListener("click", function () {
                    document.body.classList.toggle("modal-show");
                });
            });

            closeModalButton.addEventListener("click", () => {
                document.body.classList.remove('modal-show');
            });
        });
    </script>
</body>
</html>
