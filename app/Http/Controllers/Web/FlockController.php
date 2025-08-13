<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Breed;
use App\Models\Farm;
use App\Models\Flock;
use App\Models\Shed;
use Illuminate\Http\Request;

class FlockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sheds = Shed::with(['farm.owner', 'latestFlock'])->get();
        $breeds = Breed::all();
        $farms = Farm::all();
        $types = [];

        return view(
            'admin.flocks.index',
            [
                'sheds' => $sheds,
                'breeds' => $breeds,
                'farms' => $farms,
                'types' => $types,
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(Flock $flock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Flock $flock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Flock $flock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Flock $flock)
    {
        //
    }
}
