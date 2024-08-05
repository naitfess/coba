<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Surat;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;


class FileController extends Controller
{
    public function index()
    {
        return view('file.index', [
            'files' => File::filter(request(['search']))->where('user_id', auth()->user()->id)->latest()->paginate(5)->withQueryString(),
            'search' => request('search')
        ]);
    }

    public function create()
    {
        return view('file.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'file.*' => 'required|mimes:pdf|max:2048',
        ]);
        
        $validatedData['user_id'] = auth()->user()->id;
        foreach($request->file as $file) {
            $originName = $file->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;
            $file->move(public_path('media'), $fileName);

            $filePath = asset('media/'.$fileName);
            $fileSize = filesize(public_path('media/'.$fileName));

            $validatedData['name'] = $fileName;
            $validatedData['path'] = $filePath;
            $validatedData['size'] = $fileSize;
            File::create($validatedData);
        }

        
        return redirect('/files')->with('success', 'File uploaded successfully');
    }

    public function destroy($id)
    {
        try{
            $file = File::findOrFail($id);
            $filePath = public_path('media/'.$file->name);
    
            if(file_exists($filePath)){
                unlink($filePath);
            }
    
            $file->delete();
    
            return redirect('/files')->with('success', 'File deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'File deletion failed: ' . $e->getMessage());
        }
    }

    public function showOnModal(String $id)
    {
        $details = Surat::where('master_id', $id)->get();
        $files = File::where('master_id', $id)->get();

        return response()->json([
            'files' => $files,
            'details' => $details
        ]);
    }

    public function buatSurat(Request $request){
        $validator = Validator::make($request->all(),[
            'nama_surat' => 'required',
            'master_id' => 'required',
            'file' => 'nullable|mimes:pdf|max:2048',
        ]);

        $validatedData = $validator->validated();

        $newSurat = Surat::create($validatedData);

        if($request->file != null){
            $originName = $request->file->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;
            $request->file->move(public_path('media'), $fileName);
    
            $filePath = asset('media/'.$fileName);
            $fileSize = filesize(public_path('media/'.$fileName));
    
            $dataFile['name'] = $fileName;
            $dataFile['path'] = $filePath;
            $dataFile['size'] = $fileSize;
            $dataFile['surat_id'] = $newSurat->id;
            $dataFile['master_id'] = $request->master_id;
            $dataFile['user_id'] = auth()->user()->id;
            File::create($dataFile);
        }

        return redirect('/dashboard')->with('success', 'Surat berhasil ditambahkan');
    }

    public function uploadSurat(Request $request) {
        try {
            // Validasi request
            $validator = Validator::make($request->all(), [
                'master_id' => 'required',
                'files.*' => 'nullable|mimes:pdf|max:2048',
                'surat_id.*' => 'nullable'
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->with('error', $validator->errors()->first());
            }
    
            $validatedData = $validator->validated();
    
            // Cek apakah ada file yang diunggah
            if (!$request->hasFile('files')) {
                return redirect('/dashboard')->with('error', 'Silahkan input file sebelum submit');
            }
    
            // Proses setiap file yang diunggah
            foreach ($request->file('files') as $index => $file) {
                $surat_id = $request->input('surat_id')[$index] ?? null;
    
                if ($surat_id !== null) {
                    // Cek apakah ada file yang sudah terdaftar dengan master_id dan surat_id yang sama
                    $existingFile = File::where([
                        ['master_id', $validatedData['master_id']],
                        ['surat_id', $surat_id]
                    ])->first();
    
                    if ($existingFile) {
                        // Delete existing file
                        $filePath = public_path('media/' . $existingFile->name);
                        if (file_exists($filePath)) {
                            unlink($filePath);
                        }
                        $existingFile->delete();
                    }
    
                    // Proses file upload
                    $originalName = $file->getClientOriginalName();
                    $fileName = pathinfo($originalName, PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $newFileName = $fileName . '_' . time() . '.' . $extension;
                    $file->move(public_path('media'), $newFileName);
    
                    $filePath = asset('media/' . $newFileName);
                    $fileSize = filesize(public_path('media/' . $newFileName));
    
                    // Persiapkan data untuk disimpan
                    $fileData = [
                        'name' => $newFileName,
                        'path' => $filePath,
                        'size' => $fileSize,
                        'surat_id' => $surat_id,
                        'master_id' => $validatedData['master_id'],
                        'user_id' => auth()->user()->id
                    ];
    
                    // Simpan data file ke database
                    File::create($fileData);
                }
            }
    
            return redirect('/dashboard')->with('success', 'Files uploaded successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    

    public function deleteSuratFile(String $idSurat, String $idFile){
        try {
            $file = File::findOrFail($idFile);
            $filePath = public_path('media/'.$file->name);
    
            if(file_exists($filePath)){
                unlink($filePath);
            }
    
            $file->delete();

            $surat = Surat::findOrFail($idSurat);
            $surat->delete();
    
            return redirect('/dashboard')->with('success', 'File deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'File deletion failed: ' . $e->getMessage());
        }  
    }

    public function deleteSurat(String $idSurat){
        try {
            $surat = Surat::findOrFail($idSurat);
            $surat->delete();
    
            return redirect('/dashboard')->with('success', 'Surat deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Surat deletion failed: ' . $e->getMessage());
        }  
    }
}