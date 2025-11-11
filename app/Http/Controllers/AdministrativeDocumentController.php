<?php

namespace App\Http\Controllers;

use App\Models\AdministrativeDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdministrativeDocumentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $administrativeDocuments = AdministrativeDocument::when($search, function ($query, $search) {
            $query->where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('description', 'LIKE', '%' . $search . '%');
        })->latest()->paginate(10);

        return view('dashboard.administrative-document.index', [
            'page' => 'Halaman Dokumen Administrasi',
            'administrativeDocuments' => $administrativeDocuments,
            'search' => $search,
        ]);
    }

    public function download(AdministrativeDocument $administrativeDocument)
    {
        $filePath = 'assets/file/administrative-document/' . $administrativeDocument->file_path;
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->download($filePath);
        }
        return redirect()->back()->with('failed', 'File tidak ditemukan!');
    }

    public function show(AdministrativeDocument $administrativeDocument)
    {
        return view('dashboard.administrative-document.detail', [
            'page' => 'Halaman Detail Dokumen Administrasi',
            'administrativeDocument' => $administrativeDocument,
        ]);
    }

    public function create()
    {
        return view('dashboard.administrative-document.create', [
            'page' => 'Halaman Tambah Dokumen Administrasi',
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'users_id' => 'required',
                'file_path' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
                'name' => 'required|string|max:100',
                'description' => 'required|string|max:255',
            ]);

            if ($request->hasFile('file_path')) {
                $file = $request->file('file_path');
                $path = $file->store('assets/file/administrative-document', 'public');
                $validatedData['file_path'] = basename($path);
            }

            AdministrativeDocument::create($validatedData);

            return redirect()->route('administrative-document.index')->with('success', 'Berhasil menambahkan dokumen administrasi baru!');
        } catch (\Exception $e) {
            logger($e->getMessage());
            return redirect()->back()->with('failed', 'Gagal menambahkan dokumen administrasi baru!');
        }
    }

    public function edit(AdministrativeDocument $administrativeDocument)
    {
        return view('dashboard.administrative-document.edit', [
            'page' => 'Halaman Edit Dokumen Administrasi',
            'administrativeDocument' => $administrativeDocument,
        ]);
    }

    public function update(Request $request, AdministrativeDocument $administrativeDocument)
    {
        try {
            $validatedData = $request->validate([
                'file_path' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
                'name' => 'required|string|max:100',
                'description' => 'required|string|max:255',
            ]);

            if ($request->hasFile('file_path')) {
                if ($administrativeDocument->file_path && Storage::disk('public')->exists('assets/file/administrative-document/' . $administrativeDocument->file_path)) {
                    Storage::disk('public')->delete('assets/file/administrative-document/' . $administrativeDocument->file_path);
                }
                $file = $request->file('file_path');
                $path = $file->store('assets/file/administrative-document', 'public');
                $validatedData['file_path'] = basename($path);
            } else {
                $validatedData['file_path'] = $administrativeDocument->file_path;
            }

            $administrativeDocument->update($validatedData);

            return redirect()->route('administrative-document.index')->with('success', 'Berhasil mengedit dokumen administrasi!');
        } catch (\Exception $e) {
            logger($e->getMessage());
            return redirect()->back()->with('failed', 'Gagal mengedit dokumen administrasi!');
        }
    }

    public function destroy(AdministrativeDocument $administrativeDocument)
    {
        try {
            if ($administrativeDocument->file_path && Storage::disk('public')->exists('assets/file/administrative-document/' . $administrativeDocument->file_path)) {
                Storage::disk('public')->delete('assets/file/administrative-document/' . $administrativeDocument->file_path);
            }
            $administrativeDocument->delete();
            return redirect()->route('administrative-document.index')->with('success', 'Berhasil menghapus dokumen administrasi!');
        } catch (\Exception $e) {
            logger($e->getMessage());
            return redirect()->back()->with('failed', 'Gagal menghapus dokumen administrasi!');
        }
    }
}
