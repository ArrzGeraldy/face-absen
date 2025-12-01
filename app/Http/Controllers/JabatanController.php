<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JabatanController extends Controller
{
    public function index(Request $request)
    {
        // $jabatans = Jabatan::orderBy('id', 'asc')->paginate(2);
        $jabatans = Jabatan::when($request->search, function($q) use ($request){
            $q->where('nama','like', '%' . $request->search . '%');
        })->orderBy('id', 'asc')
            ->paginate(10);

        return view("admin.jabatan.index", compact('jabatans'));
    }
    
    public function create(){
        return view("admin.jabatan.create");
        
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                "nama" => "required | min:2"
            ]);
            Jabatan::create([
                "nama" => $validated['nama']
            ]);

            return redirect()->route('jabatan.index')->with('success', 'Data karyawan berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('ERROR JABATAN: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan jabatan');

        }
    }

     public function edit($id)
    {
        try {
            $jabatan = Jabatan::findOrFail($id);
            return view('admin.jabatan.edit', compact('jabatan'));
        } catch (\Exception $e) {
            Log::error('ERROR JABATAN EDIT: ' . $e->getMessage());
            return redirect()
                ->route('jabatan.index')
                ->with('error', 'Data jabatan tidak ditemukan');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                "nama" => "required|min:2"
            ]);

            $jabatan = Jabatan::findOrFail($id);
            $jabatan->update([
                "nama" => $validated['nama']
            ]);

            return redirect()
                ->route('jabatan.index')
                ->with('success', 'Data jabatan berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('ERROR JABATAN UPDATE: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui jabatan');
        }
    }

    public function destroy($id)
    {
        try {
            $jabatan = Jabatan::findOrFail($id);
            $jabatan->delete();

            return redirect()
                ->route('jabatan.index')
                ->with('success', 'Data jabatan berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('ERROR JABATAN DELETE: ' . $e->getMessage());
            return redirect()
                ->route('jabatan.index')
                ->with('error', 'Gagal menghapus jabatan');
        }
    }
}
