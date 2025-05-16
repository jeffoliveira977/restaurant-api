<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tables = Table::all();
        return response()->json($tables);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'number' => 'required|integer|unique:tables,number',
            'status' => 'sometimes|required|in:available,occupied,reserved',
        ]);

        $table = Table::create($validated);

        return response()->json($table, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Table $table)
    {
        return response()->json($table);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Table $table)
    {
        $validated = $request->validate([
            'number' => 'sometimes|required|integer|unique:tables,number,' . $table->id,
            'status' => 'sometimes|required|in:available,occupied,reserved',
        ]);

        $table->update($validated);

        return response()->json($table);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Table $table)
    {
        $table->delete();

        return response()->json(null, 204);
    }
}