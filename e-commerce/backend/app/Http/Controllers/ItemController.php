<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;

use App\Models\Item;

use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function __construct()
    {
        // -- Only authorised user can manage item details -- 
        $this->middleware('authorise')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::all();
        return response()->json($items);
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
    public function store(ItemRequest $request)
    {
        $item = new Item();
        $item->name  = $request->name;
        $item->price = $request->price;
        $item->stock = $request->stock;
        $item->save();

        return response()->json($item, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        try
        {
            $item = Item::findOrFail($id);
            return response()->json($item);
        }
        catch (ModelNotFoundException $e) 
        {
            return response()->json(['message' => 'Item not found'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ItemRequest $request, int $id)
    {
        try
        {
            $item = Item::findOrFail($id);
            $item->update($request->all());
            return response()->json($item);
        }
        catch (ModelNotFoundException $e) 
        {
            return response()->json(['message' => 'Item not found'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try
        {
            Item::findOrFail($id)->delete();
            return response()->json(['message' => 'Item deleted successfully']);
        }
        catch (ModelNotFoundException $e) 
        {
            return response()->json(['message' => 'Item not found'], Response::HTTP_NOT_FOUND);
        }
    }


}
