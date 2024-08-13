<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vote;
use App\Models\VoteDaerah;
use App\Models\Calon;
use App\Models\CalonDaerah;
use App\Models\Cabang;
use App\Models\ShowVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        $candidateType = $request->get('candidate_type', 'pusat');

        $totalUsers = User::where('role', '!=', 'admin')->count();

        $usersVotedPusat = Vote::distinct()->pluck('iduser');
        $usersVotedDaerah = VoteDaerah::distinct()->pluck('iduser');

        if ($candidateType === 'pusat') {
            $usersFullyVoted = User::whereIn('id', $usersVotedPusat)->count();
            $filteredTotalUsers = $totalUsers;
        } else {
            $cabangId = Cabang::where('name', $candidateType)->value('idcabang');
            $usersFullyVoted = User::whereIn('id', $usersVotedDaerah)
                ->where('idcabang', $cabangId)
                ->count();
            $filteredTotalUsers = User::where('idcabang', $cabangId)->count(); 
        }

        $usersNotVoted = $filteredTotalUsers - $usersFullyVoted;

        $candidates = collect();
        $cabangs = Cabang::all();

        if ($candidateType === 'pusat') {
            $candidates = Calon::all();
        } else {
            $cabang = Cabang::where('name', $candidateType)->first();
            if ($cabang) {
                $candidates = CalonDaerah::where('cabang', $cabang->name)->get();
            }
        }

        $candidatesWithVotes = $candidates->map(function ($candidate) use ($candidateType) {
            if ($candidateType === 'pusat') {
                $voteCount = Vote::where('idcalon', $candidate->idcalon)->distinct()->count('iduser');
                $totalVotes = Vote::distinct()->count('iduser');
            } else {
                $voteCount = VoteDaerah::where('idcalon', $candidate->idcaldar)->distinct()->count('iduser');
                $cabangName = Cabang::where('name', $candidate->cabang)->value('idcabang');
                $totalVotes = VoteDaerah::where('idcabang', $cabangName)->distinct()->count('iduser');
            }

            $percentage = $totalVotes ? ($voteCount / $totalVotes) * 100 : 0;

            return [
                'id' => $candidateType === 'pusat' ? $candidate->idcalon : $candidate->idcaldar,
                'ketua' => $candidate->ketua->name,
                'sekretaris' => $candidate->sekretaris->name,
                'bendahara' => $candidate->bendahara->name,
                'daerah' => $candidateType === 'pusat' ? '-' : ($candidate->cabang === 'Tanjungenim' ? 'Tanjung Enim' : $candidate->cabang),
                'vote_count' => $voteCount,
                'percentage' => number_format($percentage, 2)
            ];
        });

        if ($candidateType === 'pusat') {
            $voteKosongCount = Vote::whereNull('idcalon')->distinct()->count('iduser');
            $totalVotes = Vote::distinct()->count('iduser');
        } else {
            $cabang = Cabang::where('name', $candidateType)->first();
            $voteKosongCount = VoteDaerah::whereNull('idcalon')->where('idcabang', $cabang->idcabang)->distinct()->count('iduser');
            $totalVotes = VoteDaerah::where('idcabang', $cabang->idcabang)->distinct()->count('iduser');
        }

        $voteKosongPercentage = $totalVotes ? number_format(($voteKosongCount / $totalVotes) * 100, 2) : 0;

        $showVoteKosong = $candidates->isNotEmpty() || $voteKosongCount > 0;

        $showVote = ShowVote::first()->option; 

        return view('admin.dashboard', compact(
            'filteredTotalUsers', 
            'usersFullyVoted',
            'usersNotVoted',
            'candidatesWithVotes',
            'candidateType',
            'cabangs',
            'showVoteKosong',
            'voteKosongCount',
            'voteKosongPercentage',
            'showVote'
        ));
    }

    public function toggleShowVote()
    {
        $showVote = ShowVote::first();
        if ($showVote) {
            $showVote->option = $showVote->option === 'yes' ? 'no' : 'yes';
            $showVote->save();
        }

        return redirect()->route('admin')->with('success', 'Show vote option updated successfully.');
    }
}

