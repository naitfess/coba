<?php

namespace App\Http\Controllers;

use App\Models\Badminton;
use Illuminate\Http\Request;

class BadmintonController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Badminton $badminton)
    {
        return view('badminton.update', [
            'badminton' => $badminton
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Badminton $badminton)
    {
        $validatedData = $request->validate([
            'badminton-name' => 'required',
            'badminton-category_kelas' => 'required'
        ]);

        Badminton::where('id', $badminton->id)->update([
            'name' => $validatedData['badminton-name'],
            'category_kelas' => $validatedData['badminton-category_kelas']
        ]);

        return redirect("/dashboard/{$badminton->master_id}")->with('success', 'Data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Badminton $badminton)
    {   
        $id = $badminton->master_id;
        Badminton::destroy($badminton->id);
        return redirect("/dashboard/{$id}")->with('success', 'Data deleted successfully');
    }
}
