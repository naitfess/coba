<?php

namespace App\Http\Controllers;

use App\Models\Football;
use Illuminate\Http\Request;

class FootballController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Football $football)
    {
        return view('football.update', [
            'football' => $football
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Football $football)
    {
        $validatedData = $request->validate([
            'football-name' => 'required',
            'football-category_umur' => 'required'
        ]);

        Football::where('id', $football->id)->update([
            'name' => $validatedData['football-name'],
            'category_umur' => $validatedData['football-category_umur']
        ]);

        return redirect("/dashboard/{$football->master_id}")->with('success', 'Data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Football $football)
    {
        $id = $football->master_id;
        Football::destroy($football->id);
        return redirect("/dashboard/{$id}")->with('success', 'Data deleted successfully');
    }
}
