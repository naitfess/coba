<?php

namespace App\Http\Controllers;

use App\Models\Master;
use App\Models\Football;
use App\Models\Swimming;
use App\Models\Badminton;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\MasterResource;
use App\Http\Requests\MasterUpdateRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MasterController extends Controller
{
    public function index()
    {
        return redirect('/');
    }

    public function create()
    {
        return view('create',[
            'masters' => Master::all()
        ]);
    }

    public function store(Request $request)
    {   
        
        // if($request->hasFile('upload')){
        //     $originName = $request->file('upload')->getClientOriginalName();
        //     $fileName = pathinfo($originName, PATHINFO_FILENAME);
        //     $extension = $request->file('upload')->getClientOriginalExtension();
        //     $fileName = $fileName.'_'.time().'.'.$extension;
        //     $request->file('upload')->move(public_path('media'), $fileName);

        //     $url = asset('media/'.$fileName);
        //     return response()->json(['filename' => $fileName, 'uploaded' => 1,'url' => $url]);
        // }
        
        $request->validate([
            'select-event' => 'required',
        ]);
        
        if($request['select-event'] == 'new'){
            $validatedData = $request->validate([
                'name' => 'required|unique:masters',
                'year' => 'required',
                'location' => 'required',
                'description' => 'nullable',
            ]);
        }

        $request->validate([
        'category_id' => 'required',
        ]);
    
    if($request['category_id'] == '1'){
        $validatedDataDetail = $request->validate([
            'swimming-name' => [
                'required',
                Rule::unique('swimmings', 'name')->where('master_id', $request->input('select-event')),
            ],
            'swimming-distance' => 'required',
            'swimming-stroke' => 'required',
        ]);
        if(isset($validatedData)){
            $master = Master::create($validatedData);
        }
        Swimming::create([
            'name' => $validatedDataDetail['swimming-name'],
            'distance' => $validatedDataDetail['swimming-distance'],
            'stroke' => $validatedDataDetail['swimming-stroke'],
            'master_id' => $master->id ?? $request['select-event'],
            'category_id' => $request['category_id'],
        ]);
    }
    
    if($request['category_id'] == '2'){
        $validatedDataDetail = $request->validate([
            'football-name' => [
                'required',
                Rule::unique('footballs', 'name')->where('master_id', $request->input('select-event')),
            ],
            'football-category_umur' => 'required',
        ]);
        if(isset($validatedData)){
            $master = Master::create($validatedData);
        }
        Football::create([
            'name' => $validatedDataDetail['football-name'],
            'category_umur' => $validatedDataDetail['football-category_umur'],
            'master_id' => $master->id ?? $request['select-event'],
            'category_id' => $request['category_id'],
        ]);
    }
        
        if($request['category_id'] == '3'){
            $validatedDataDetail = $request->validate([
                'badminton-name' => [
                    'required',
                    Rule::unique('badmintons', 'name')->where('master_id', $request->input('select-event')),
                ],
                'badminton-category_kelas' => 'required',
            ]);
            if(isset($validatedData)){
                $master = Master::create($validatedData);
            }   
            Badminton::create([
                'name' => $validatedDataDetail['badminton-name'],
                'category_kelas' => $validatedDataDetail['badminton-category_kelas'],
                'master_id' => $master->id ?? $request['select-event'],
                'category_id' => $request['category_id'],
            ]);
        }

        return redirect('/dashboard')->with('success', 'Event berhasil ditambahkan');

    }

    public function show(Master $master)
    {
        return view('show', [
            'master' => $master,
            'swimmings' => Swimming::where('master_id', $master->id)->filter(request(['search']))->latest()->get(),
            'footballs' => Football::where('master_id', $master->id)->filter(request(['search']))->latest()->get(),
            'badmintons' => Badminton::where('master_id', $master->id)->filter(request(['search']))->latest()->get(),
            'search' => request('search')
        ]);
    }

    public function edit(Master $master)
    {
        return view('update', [
            'master' => $master,
        ]);
    }

    public function update(Request $request, Master $master)
    {   
        $validatedData = $request->validate([
            'name' => 'required',
            'year' => 'required',
            'location' => 'required',
            'description' => 'nullable',
        ]);

        Master::where('id', $master->id)
            ->update($validatedData);
        return redirect('/dashboard')->with('success', 'Data berhasil diubah');
    }

    public function destroy($id)
    {
        Swimming::where('master_id', $id)->delete();
        Football::where('master_id', $id)->delete();
        Badminton::where('master_id', $id)->delete();
        Master::destroy($id);

        return redirect('/')->with('success', 'Data berhasil dihapus');
    }
}
