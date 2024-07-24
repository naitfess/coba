<?php

namespace App\Http\Controllers;

use App\Models\Master;
use App\Models\Swimming;
use Illuminate\Http\Request;

class SwimmingController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Swimming $swimming)
    {
        return view('swimming.update', [
            'swimming' => $swimming
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Swimming $swimming)
    {
        $validatedData = $request->validate([
            'swimming-name' => 'required',
            'swimming-distance' => 'required',
            'swimming-stroke' => 'required',
        ]);

        Swimming::where('id', $swimming->id)->update([
            'name' => $validatedData['swimming-name'],
            'distance' => $validatedData['swimming-distance'],
            'stroke' => $validatedData['swimming-stroke']
        ]);

        return redirect("/dashboard/{$swimming->master_id}")->with('success', 'Data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Swimming $swimming)
    {
        $id = $swimming->master_id;
        Swimming::destroy($swimming->id);
        return redirect("/dashboard/{$id}")->with('success', 'Data deleted successfully');
    }
}
