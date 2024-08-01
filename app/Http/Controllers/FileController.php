<?php

namespace App\Http\Controllers;

use App\Models\File;
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
        File::destroy($id);
        return redirect('/files')->with('success', 'File berhasil dihapus');
    }

    public function showOnModal(String $id)
    {
        $renang = File::where([
            ['master_id', $id],
            ['surat_id', 1]
            ])->first();
        
        $bola = File::where([
            ['master_id', $id],
            ['surat_id', 2]
            ])->first();

        $badminton = File::where([  
            ['master_id', $id],
            ['surat_id', 3]
            ])->first();

        $file = [
            'renang' => $renang,
            'bola' => $bola,
            'badminton' => $badminton
        ];

        return response()->json($file);
    }

    public function uploadSurat(Request $request){
        // $validatedData = $request->validate([
        //     'master_id' => 'required',
        //     'file.*' => 'nullable|mimes:pdf|max:2048',
        //     'surat_id.*' => 'nullable'
        // ]);

        $validator = Validator::make($request->all(), [
            'master_id' => 'required',
            'file.*' => 'nullable|mimes:pdf|max:2048',
            'surat_id.*' => 'nullable'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'The file must be a pdf.');
        }

        $validatedData = $validator->validated();

        if($request->file == null){
            return redirect('/dashboard')->with('error', 'Silahkan input file sebelum submit');
        }

        $validatedData['user_id'] = auth()->user()->id;

        foreach($request->file as $index => $file) {

            //check file udah ada blm
            $checkSurat = File::where([
                ['master_id', $validatedData['master_id']],
                ['surat_id', $index+1]
            ])->first();

            if($checkSurat){
                $checkSurat->update([
                    'master_id' => null,
                    'surat_id' => null,
                ]);
            }

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
            $validatedData['surat_id'] = $index+1;
            File::create($validatedData);
        }

        return redirect('/dashboard')->with('success', 'File uploaded successfully');
    }

    public function deleteSurat(String $id){
        $file = File::find($id);
        $file->update([
            'master_id' => null,
            'surat_id' => null,
        ]);

        return redirect('/dashboard')->with('success', 'File deleted successfully');
    }
}