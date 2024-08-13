<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Calon;
use App\Models\CalonDaerah;
use App\Models\Vote;
use App\Models\VoteDaerah;
use App\Models\Cabang;
use App\Models\ShowVote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function showVotePage()
    {
        $userId = Auth::id();
        $user = User::with('cabang')->find($userId);

        // Check if the user has already voted
        $hasVotedPusat = Vote::where('iduser', $userId)->exists();
        $hasVotedDaerah = VoteDaerah::where('iduser', $userId)->exists();

        if ($hasVotedPusat && $hasVotedDaerah) {
            // If the user has already voted, redirect to the finishvote page
            return redirect()->route('user.finishvote');
        }

        // Pusat
        $calons = Calon::with(['ketua', 'sekretaris', 'bendahara'])
            ->get()
            ->map(function ($calon) {
                $calon->ketua->cabang_name = $this->getRegionName($calon->ketua->idcabang);
                $calon->sekretaris->cabang_name = $this->getRegionName($calon->sekretaris->idcabang);
                $calon->bendahara->cabang_name = $this->getRegionName($calon->bendahara->idcabang);
                return $calon;
            });

        // Daerah
        $regionName = $this->getRegionName($user->idcabang);
        $calondaerahs = CalonDaerah::where('cabang', $regionName)->with(['ketua', 'sekretaris', 'bendahara'])->get();

        return view('user.votepage', compact('calons', 'calondaerahs', 'regionName'));
    }


    public function vote(Request $request)
    {
        $request->validate([
            'pusat_candidate_id' => 'nullable|exists:calons,idcalon',
            'daerah_candidate_id' => 'nullable|exists:calondaerah,idcaldar',
        ]);

        $userId = Auth::id();
        $user = Auth::user();

        if (Vote::where('iduser', $userId)->exists() || VoteDaerah::where('iduser', $userId)->exists()) {
            return redirect()->route('user.finishvote')->with('error', 'You have already voted.');
        }

        Vote::create([
            'iduser' => $userId,
            'idcalon' => $request->pusat_candidate_id === '' ? null : $request->pusat_candidate_id,
        ]);

        VoteDaerah::create([
            'iduser' => $userId,
            'idcalon' => $request->daerah_candidate_id === '' ? null : $request->daerah_candidate_id,
            'idcabang' => $user->idcabang,
        ]);

        return redirect()->route('user.finishvote')->with('success', 'Your votes have been submitted.');
    }
    
    private function getRegionName($idcabang)
    {
        $regionNames = [
            1 => 'Jakarta',
            2 => 'Lampung',
            3 => 'Palembang',
            4 => 'Padang',
            5 => 'Tanjung Enim',
        ];

        return $regionNames[$idcabang] ?? 'Unknown';
    }

    public function finishvote(Request $request)
    {
        $candidateType = $request->input('candidate_type', 'pusat');

        $displayCandidateType = $this->getRegionNameMapping($candidateType);

        $candidates = $this->getCandidates($candidateType);

        $candidatesWithVotes = $candidates->map(function ($candidate) use ($candidateType) {
            $voteCount = $this->getVoteCount($candidate, $candidateType);
            $totalVotes = $this->getTotalVotes($candidateType);
            $percentage = $this->calculatePercentage($voteCount, $totalVotes);

            return [
                'id' => $candidateType === 'pusat' ? $candidate->idcalon : $candidate->idcaldar,
                'ketua' => $candidate->ketua->name,
                'sekretaris' => $candidate->sekretaris->name,
                'bendahara' => $candidate->bendahara->name,
                'vote_count' => $voteCount,
                'percentage' => number_format($percentage, 2),
            ];
        });

        $voteKosongCount = $this->getVoteKosongCount($candidateType);
        $totalVotes = $this->getTotalVotes($candidateType);
        $voteKosongPercentage = $this->calculatePercentage($voteKosongCount, $totalVotes);
        $voteKosongPercentage = number_format($voteKosongPercentage, 2);

        $showVote = ShowVote::first(); 

        return view('user.finishvote', [
            'candidateType' => $candidateType,
            'displayCandidateType' => $displayCandidateType,
            'candidatesWithVotes' => $candidatesWithVotes,
            'showVoteKosong' => $candidates->isNotEmpty() || $voteKosongCount > 0,
            'voteKosongCount' => $voteKosongCount,
            'voteKosongPercentage' => $voteKosongPercentage,
            'showVote' => $showVote->option ?? 'no', 
        ]);
    }

    private function calculatePercentage($count, $total)
    {
        return $total ? ($count / $total) * 100 : 0;
    }

    protected function getCandidates($candidateType)
    {
        if ($candidateType === 'pusat') {
            return Calon::with('ketua', 'sekretaris', 'bendahara')->get();
        } else {
            return CalonDaerah::where('cabang', $candidateType)->with('ketua', 'sekretaris', 'bendahara')->get();
        }
    }

    private function getVoteCount($candidate, $candidateType)
    {
        if ($candidateType === 'pusat') {
            return Vote::where('idcalon', $candidate->idcalon)->distinct()->count('iduser');
        }

        return VoteDaerah::where('idcalon', $candidate->idcaldar)->distinct()->count('iduser');
    }

    private function getTotalVotes($candidateType)
    {
        if ($candidateType === 'pusat') {
            return Vote::distinct()->count('iduser');
        }

        $cabang = Cabang::where('name', $candidateType)->first();
        return $cabang ? VoteDaerah::where('idcabang', $cabang->idcabang)->distinct()->count('iduser') : 0;
    }

    private function getVoteKosongCount($candidateType)
    {
        if ($candidateType === 'pusat') {
            return Vote::whereNull('idcalon')->distinct()->count('iduser');
        }

        $cabang = Cabang::where('name', $candidateType)->first();
        return $cabang ? VoteDaerah::where('idcabang', $cabang->idcabang)->whereNull('idcalon')->distinct()->count('iduser') : 0;
    }

    private function getRegionNameMapping($candidateType)
    {
        $regionMappings = [
            'jakarta' => 'Jakarta',
            'lampung' => 'Lampung',
            'palembang' => 'Palembang',
            'padang' => 'Padang',
            'pusat' => 'Central',
            'tanjungenim' => 'Tanjung Enim',
        ];

        return $regionMappings[strtolower($candidateType)] ?? ucfirst($candidateType);
    }

    public function showCalonDetail($idcalon)
    {
        $calon = Calon::with(['ketua', 'sekretaris', 'bendahara'])
            ->findOrFail($idcalon);

        $calon->ketua->cabang_name = $this->getRegionName($calon->ketua->idcabang);
        $calon->sekretaris->cabang_name = $this->getRegionName($calon->sekretaris->idcabang);
        $calon->bendahara->cabang_name = $this->getRegionName($calon->bendahara->idcabang);

        return view('user.detailpusat', compact('calon'));
    }

    public function showCalonDaerahDetail($idcaldar)
    {
        $calondaerah = CalonDaerah::with(['ketua', 'sekretaris', 'bendahara'])->findOrFail($idcaldar);
        return view('user.detaildaerah', compact('calondaerah'));
    }
}
