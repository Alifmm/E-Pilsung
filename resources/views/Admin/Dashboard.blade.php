<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    @include('partials.sidebar')
    <div class="container">
        <section class="card">
            <div class="vote-card"></div>
            <div class="card-box">
                <div class="card-wrap">
                    <div class="btn-success cursor">
                        <h3>{{ $usersNotVoted }}</h3>
                        <p>Pemilih belum Memilih</p>
                    </div>
                </div>
                <div class="card-wrap">
                    <div class="btn-primary cursor">
                        <h3>{{ $usersFullyVoted }}</h3>
                        <p>Pemilih yang Sudah Memilih</p>
                    </div>
                </div>
                <div class="card-wrap">
                    <div class="btn-danger cursor">
                        <h3>{{ $filteredTotalUsers }}</h3>
                        <p>Jumlah Pemilih {{ ucfirst($candidateType) }}</p>
                    </div>
                </div>
            </div>
            <div class="container">
                <h2 class="heading-left">Perolehan Suara</h2>
                <form method="GET" action="{{ route('admin') }}" id="candidateForm" class="form-container">
                    <select name="candidate_type" id="candidate_type" class="form-select" aria-label="Default select example" onchange="document.getElementById('candidateForm').submit()">
                        <option value="pusat" @if($candidateType == 'pusat') selected @endif>Pusat</option>
                        @foreach ($cabangs as $cabang)
                            <option value="{{ $cabang->name }}" @if($candidateType == $cabang->name) selected @endif>{{ $cabang->name }}</option>
                        @endforeach
                    </select>
                </form>
                <div class="vote-card"></div>
                <div class="card-box">
                    <div class="card-wrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Ketua</th>
                                    <th>Nama Sekretaris</th>
                                    <th>Nama Bendahara</th>
                                    <th>Daerah</th>
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
                                        <td>{{ $candidate['daerah'] }}</td>
                                        <td>{{ $candidate['vote_count'] }}</td>
                                        <td>{{ $candidate['percentage'] }}%</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">No candidates found.</td>
                                    </tr>
                                @endforelse
                                @if ($showVoteKosong)
                                    <tr>
                                        <td colspan="5">Vote Kosong</td>
                                        <td>{{ $voteKosongCount }}</td>
                                        <td>{{ $voteKosongPercentage }}%</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="form-inline">
                    <form method="POST" action="{{ route('admin.toggleShowVote') }}">
                        @csrf
                        <button type="submit" class="btn btn-warning">
                            {{ $showVote === 'yes' ? 'Hide Voting Results' : 'Show Voting Results' }}
                        </button>
                    </form>
                </div>
            </div>
        </section>
    </div>
    <script>
        document.getElementById('candidate_type').addEventListener('change', function() {
            document.getElementById('candidateForm').submit();
        });
    </script>
</body>
</html>
