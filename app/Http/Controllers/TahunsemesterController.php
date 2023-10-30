<?php

namespace App\Http\Controllers;

use App\Models\Tahunsemester;
use Illuminate\Http\Request;

class TahunsemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //index tahun semster
        if(Auth()->user()->nmmhs != 'admin'){
            //return 404
            return view('errors.403'); 
        }
        $tahunsemesters = Tahunsemester::all()->sortByDesc('thsms');
        

        return view('tahunsemesters.index', compact('tahunsemesters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //create tahunsemester
        return view('tahunsemesters.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //store 
        // dd($request->thsms);
        $tahunsemester = Tahunsemester::create([
            'thsms' => $request->thsms,
            'status' => 'tidak_aktif'
        ]);

        return redirect()->route('tahunsemesters.index');
    }

    /**
     * Display the specified resource.
     */
    // public function show(Tahunsemester $tahunsemester)
    // {
    
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tahunsemester $tahunsemester)
    {
        //edit tahunsemester

        return view('tahunsemesters.edit', compact('tahunsemester'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tahunsemester $tahunsemester)
    {
        if(Tahunsemester::where('status', 'aktif')->exists()&&$request->status=='aktif'){
            //return 404
            return redirect()->back()->with('pesan', 'Tahun semester aktif sudah ada');
        }
        
        //update tahunsemester
        $tahunsemester->update([
            'thsms' => $request->thsms,
            'status' => $request->status
        ]);
        $tahunsemester->save();
        return redirect()->route('tahunsemesters.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tahunsemester $tahunsemester)
    {
        //destroy tahunsemester

        $tahunsemester->delete();

        return redirect()->back();
    }
}
