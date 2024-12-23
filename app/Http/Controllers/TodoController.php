<?php

namespace App\Http\Controllers;

use App\Events\TodoCompleted;
use App\Events\TodoCreated;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todos = Todo::orderBy('isCompleted', 'asc')->orderBy('created_at', 'desc')->get();
        return response()->json($todos);
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'isCompleted' => 'required|boolean',
        ]);
        $todo = Todo::create($validated);
        event(new TodoCreated($todo));
        return response()->json($todo, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try{
            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|nullable|string',
                'isCompleted' => 'sometimes|required|boolean',
            ]);

            $todo = Todo::findOrFail($id);

            $wasCompleted = $todo->isCompleted;

            $todo->update($validated);

            if (!$wasCompleted && $todo->isCompleted) {
                broadcast(new TodoCompleted($todo));
            }

            return response()->json($todo);
        }catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    } 

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $todo = Todo::findOrFail($id);
        $todo->delete();

        return response()->json(['message' => 'Todo deleted safely'], 200);
    }
}
