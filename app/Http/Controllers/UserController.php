<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    // mostrar tareas
    public function index(){
        $users = User::all();
        return response()->json($users);
    }
}
