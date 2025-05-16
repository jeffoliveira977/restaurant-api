<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MenuItem::query();
        
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $items = $query->with('category')->get();
        return response()->json($items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:menu_categories,id',
            'available' => 'sometimes|boolean',
            'preparation_time' => 'nullable|integer|min:0',
        ]);

        $item = MenuItem::create($validated);

        return response()->json($item, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MenuItem $item)
    {
        return response()->json($item->load('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MenuItem $item)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'category_id' => 'sometimes|required|exists:menu_categories,id',
            'available' => 'sometimes|boolean',
            'preparation_time' => 'nullable|integer|min:0',
        ]);

        $item->update($validated);

        return response()->json($item);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MenuItem $item)
    {
        $item->delete();

        return response()->json(null, 204);
    }
}