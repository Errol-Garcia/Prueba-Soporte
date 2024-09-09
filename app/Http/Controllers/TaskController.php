<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    // mostrar tareas
    public function index(){
        $tasks = Task::with('user')->get();
        return response()->json($tasks);
    }
    // Crear tarea
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:500',
            'user' => 'required|max:500',
        ]);

        $task = new Task($validated);
        $user = User::where('email',$validated['user'])->first();
        if ($user != null){
             $task->user_id = $user->id;
            $task->save();

            return response()->json([
                'message'=> 'Task created successfully.'
            ], Response::HTTP_ACCEPTED);
        }else{
            return response()->json([
                'message'=> 'Task created successfully.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Actualizar tarea
    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if(!$task) {
            return redirect()->back()->with('error', 'Task not found.');
        }
         $task->completed = 1;
        // Corrección: Se actualiza la tarea con datos validados.
        $task->update();
        return response()->json([
            'message'=> 'Task updated successfully.'
        ], Response::HTTP_ACCEPTED);
    }

    // Eliminar tarea
    public function destroy($id)
    {
        $task = Task::find($id);

        if(!$task) {
            return redirect()->back()->with('error', 'Task not found.');
        }

        $task->delete();

        return response()->json([
            'message'=> 'Task deleted successfully.'
        ], Response::HTTP_ACCEPTED);
    }
}
