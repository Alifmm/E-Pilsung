<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calon;
use App\Models\CalonDaerah;
use App\Models\Cabang;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CalonController extends Controller
{
    protected $request;
    protected $calon;
    protected $calonDaerah;
    protected $cabang;

    public function __construct(Request $request, Calon $calon, CalonDaerah $calonDaerah, Cabang $cabang)
    {
        $this->request = $request;
        $this->calon = $calon;
        $this->calonDaerah = $calonDaerah;
        $this->cabang = $cabang;
    }

    public function index()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }
    
        // Mapping array for cabang names
        $cabangNames = [
            1 => 'Jakarta',
            2 => 'Lampung',
            3 => 'Palembang',
            4 => 'Padang',
            5 => 'Tanjung Enim'
        ];
    
        $calons = Calon::with(['ketua', 'sekretaris', 'bendahara'])
            ->get()
            ->map(function ($calon) use ($cabangNames) {
                $calon->ketua_cabang_name = $cabangNames[$calon->ketua->idcabang] ?? 'Unknown';
                $calon->sekretaris_cabang_name = $cabangNames[$calon->sekretaris->idcabang] ?? 'Unknown';
                $calon->bendahara_cabang_name = $cabangNames[$calon->bendahara->idcabang] ?? 'Unknown';
                return $calon;
            });
    
        $calondaerahs = CalonDaerah::with(['ketua', 'sekretaris', 'bendahara'])->get();
        return view('admin.manajemencalon', compact('calons', 'calondaerahs'));
    }
    
    

    public function create()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }
    
        $cabangs = $this->cabang->all();
    
        $existingCalonUsers = Calon::pluck('idketua')->merge(Calon::pluck('idsekretaris'))->merge(Calon::pluck('idbendahara'))->toArray();
        $existingCalonDaerahUsers = CalonDaerah::pluck('idketua')->merge(CalonDaerah::pluck('idsekretaris'))->merge(CalonDaerah::pluck('idbendahara'))->toArray();
        $existingUsers = array_merge($existingCalonUsers, $existingCalonDaerahUsers);
        
        $users = User::where('role', '!=', 'admin')
                    ->whereNotIn('id', $existingUsers)
                    ->get();
    
        return view('admin.manajemencaloncreate', compact('users', 'cabangs'));
    }
    

    public function store(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'type' => 'required|in:calon,calondaerahpalembang,calondaerahpadang,calondaerahlampung,calondaerahjakarta,calondaerahtanjungenim',
            'idketua' => 'required|exists:users,id',
            'wajahketua' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'idsekretaris' => 'required|exists:users,id',
            'wajahsekretaris' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'idbendahara' => 'required|exists:users,id',
            'wajahbendahara' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'visi' => 'required|string',
            'misi' => 'required|string',
        ]);

        $data = $request->all();
        $data['wajahketua'] = $request->file('wajahketua')->store('wajahcalon', 'public');
        $data['wajahsekretaris'] = $request->file('wajahsekretaris')->store('wajahcalon', 'public');
        $data['wajahbendahara'] = $request->file('wajahbendahara')->store('wajahcalon', 'public');

        $cabangMap = [
            'calondaerahjakarta' => 'Jakarta',
            'calondaerahlampung' => 'Lampung',
            'calondaerahpalembang' => 'Palembang',
            'calondaerahpadang' => 'Padang',
            'calondaerahtanjungenim' => 'Tanjung Enim'
        ];

        if (strpos($request->type, 'calondaerah') === 0) {
            $data['cabang'] = $cabangMap[$request->type];
        }

        if ($request->type == 'calon') {
            Calon::create($data);
        } else {
            CalonDaerah::create($data);
        }

        return redirect()->route('calons.index')->with('success', 'Candidate created successfully.');
    }

    public function editCalon(Calon $calon)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        return view('admin.manajemencalonedit', compact('calon'));
    }

    public function updateCalon(Request $request, Calon $calon)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'wajahketua' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'wajahsekretaris' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'wajahbendahara' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'visi' => 'required|string',
            'misi' => 'required|string',
        ]);

        if ($request->hasFile('wajahketua')) {
            Storage::delete('public/'.$calon->wajahketua);
            $calon->wajahketua = $request->file('wajahketua')->store('wajahcalon', 'public');
        }

        if ($request->hasFile('wajahsekretaris')) {
            Storage::delete('public/'.$calon->wajahsekretaris);
            $calon->wajahsekretaris = $request->file('wajahsekretaris')->store('wajahcalon', 'public');
        }

        if ($request->hasFile('wajahbendahara')) {
            Storage::delete('public/'.$calon->wajahbendahara);
            $calon->wajahbendahara = $request->file('wajahbendahara')->store('wajahcalon', 'public');
        }

        $calon->update([
            'visi' => $request->visi,
            'misi' => $request->misi,
        ]);

        return redirect()->route('calons.index')->with('success', 'Candidate updated successfully.');
    }

    public function destroyCalon(Calon $calon)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        Storage::delete('public/'.$calon->wajahketua);
        Storage::delete('public/'.$calon->wajahsekretaris);
        Storage::delete('public/'.$calon->wajahbendahara);
        $calon->delete();

        return redirect()->route('calons.index')->with('success', 'Candidate deleted successfully.');
    }

    public function editCalonDaerah(CalonDaerah $calondaerah)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        return view('admin.manajemencalondaerahedit', compact('calondaerah'));
    }

    public function updateCalonDaerah(Request $request, CalonDaerah $calondaerah)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'wajahketua' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'wajahsekretaris' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'wajahbendahara' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'visi' => 'required|string',
            'misi' => 'required|string',
        ]);

        if ($request->hasFile('wajahketua')) {
            Storage::delete('public/'.$calondaerah->wajahketua);
            $calondaerah->wajahketua = $request->file('wajahketua')->store('wajahcalon', 'public');
        }

        if ($request->hasFile('wajahsekretaris')) {
            Storage::delete('public/'.$calondaerah->wajahsekretaris);
            $calondaerah->wajahsekretaris = $request->file('wajahsekretaris')->store('wajahcalon', 'public');
        }

        if ($request->hasFile('wajahbendahara')) {
            Storage::delete('public/'.$calondaerah->wajahbendahara);
            $calondaerah->wajahbendahara = $request->file('wajahbendahara')->store('wajahcalon', 'public');
        }

        $calondaerah->update([
            'visi' => $request->visi,
            'misi' => $request->misi,
        ]);

        return redirect()->route('calons.index')->with('success', 'Candidate updated successfully.');
    }

    public function destroyCalonDaerah(CalonDaerah $calondaerah)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access.');
        }

        Storage::delete('public/'.$calondaerah->wajahketua);
        Storage::delete('public/'.$calondaerah->wajahsekretaris);
        Storage::delete('public/'.$calondaerah->wajahbendahara);
        $calondaerah->delete();

        return redirect()->route('calons.index')->with('success', 'Candidate deleted successfully.');
    }
}
