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
        $tahunsemester = Tahunsemester::all();

        return view('tahunsemesters.index', compact('tahunsemester'));
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
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tahunsemester $tahunsemester)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tahunsemester $tahunsemester)
    {
        //
    }
}
