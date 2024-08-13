<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/selesai.css') }}">
    <title>Voting Selesai</title>
</head>
<body>
    <header class="header">
        <div class="header-logo">
            <img src="{{ asset('img/logo.png') }}" alt="Bukit Asam">
        </div>
        <div class="profile">
            <div class="profile-wrap">
                <h4 class="profile-name">{{ Auth::user()->name }}</h4>
            </div>
        </div>
    </header>
    <main class="container">
        <section class="vote">
            <h2 class="vote-title">Voting Anda Telah Selesai</h2>
            <div class="vote-box">
                <p class="btn btn-primary">Terima kasih telah berpartisipasi!</p>
                <p class="small-text">Kami menghargai partisipasi Anda dalam pemilihan Ketua, Sekretaris, dan Bendahara untuk Perusahaan Pusat dan Cabang. Partisipasi Anda sangat penting dalam menentukan pemimpin yang akan membawa perusahaan kita menuju masa depan yang lebih baik.</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </section>

        @if ($showVote === 'yes')
        <section class="dropdown-section">
            <h2>Hasil Pungutan Suara</h2>
            <form action="{{ route('user.finishvote') }}" method="GET">
                <select name="candidate_type" id="candidate_type" onchange="this.form.submit()">
                    <option value="pusat" {{ $candidateType === 'pusat' ? 'selected' : '' }}>Pusat</option>
                    <option value="Jakarta" {{ $candidateType === 'Jakarta' ? 'selected' : '' }}>Jakarta</option>
                    <option value="Lampung" {{ $candidateType === 'Lampung' ? 'selected' : '' }}>Lampung</option>
                    <option value="Palembang" {{ $candidateType === 'Palembang' ? 'selected' : '' }}>Palembang</option>
                    <option value="Padang" {{ $candidateType === 'Padang' ? 'selected' : '' }}>Padang</option>
                    <option value="Tanjung Enim" {{ $candidateType === 'Tanjung Enim' ? 'selected' : '' }}>Tanjung Enim</option>
                </select>                
            </form>
            
            <div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Ketua</th>
                            <th>Nama Sekretaris</th>
                            <th>Nama Bendahara</th>
                            <th>Jumlah Suara</th>
                            <th>Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($candidatesWithVotes as $candidate)
                            <tr>
                                <td>{{ $candidate['id'] }}</td>
                                <td>{{ $candidate['ketua'] }}</td>
                                <td>{{ $candidate['sekretaris'] }}</td>
                                <td>{{ $candidate['bendahara'] }}</td>
                                <td>{{ $candidate['vote_count'] }}</td>
                                <td>{{ number_format($candidate['percentage'], 2) }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">Tidak ada kandidat yang ditemukan.</td>
                            </tr>
                        @endforelse

                        @if ($showVoteKosong && $voteKosongCount > 0)
                            <tr>
                                <td></td>
                                <td colspan="3">Vote Kosong</td>
                                <td>{{ $voteKosongCount }}</td>
                                <td>{{ number_format($voteKosongPercentage, 2) }}%</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </section>
        @endif
    </main>
</body>
</html>
