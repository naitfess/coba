<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FileController extends Controller
{
    public function index()
    {
        return view('file.index', [
            'files' => File::where('user_id', auth()->user()->id)->get(),
            // 'files' => File::filter(request(['search']))->latest()->paginate(5)->withQueryString(),
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
            'name' => [
                'required',
                Rule::unique('files', 'name')->where('user_id', auth()->user()->id)
            ],
            'file' => 'required|mimes:pdf|max:1024',
            'description' => 'nullable',
        ]);

        if($request->hasFile('file')){
            $originName = $request->file('file')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('file')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;
            $request->file('file')->move(public_path('media'), $fileName);

            $filePath = asset('media/'.$fileName);
            $fileSize = filesize(public_path('media/'.$fileName));

            $validatedData['path'] = $filePath;
            $validatedData['size'] = $fileSize;
            // return response()->json(['filename' => $fileName, 'uploaded' => 1,'url' => $file]);
        }

        $validatedData['user_id'] = auth()->user()->id;
        File::create($validatedData);
        
        return redirect('/files')->with('success', 'File uploaded successfully');
    }
}
