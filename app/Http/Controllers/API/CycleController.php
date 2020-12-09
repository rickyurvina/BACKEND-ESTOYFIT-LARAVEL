<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Models\Cycle;

class CycleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cycles = Cycle::where('status', 1)->get();

        return response()->json([
            'message' => 'Response',
            'data' => $cycles
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required|max:255',
        ]);

        $objectCreate = [
            'name' => $request->input('name'),
            'description' => $request->input('description')
        ];

        $rowCreated = Cycle::create($objectCreate);
 
        return response()->json([
            'message' => 'Satisfactory',
            'data' => $rowCreated
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required|max:255',
        ]);

        $objectUpdate = [
            'name' => $request->input('name'),
            'description' => $request->input('description')
        ];

        $cycle = Cycle::find($id)->update($objectUpdate);
        $cycles = Cycle::All();
 
        return response()->json([
            'message' => 'Satisfactory'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cycle = Cycle::find($id)->update(['status' => "0"]);
        $cycles = Cycle::All();
 
        return response()->json([
            'message' => 'Satisfactory'
        ], 200);
    }
}
