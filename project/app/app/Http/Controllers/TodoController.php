<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Todo;
use JWTAuth;

class TodoController extends Controller
{

    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $todos = Todo::where('owner_id', $user->id)->get();
        return $this->successResponse($todos->toArray());
    }

    public function store(Request $request)
    {
        $newTodo = $request->all();
        $newTodo['owner_id'] = $request->authUser->id;

        $savedTodo = Todo::create($newTodo);
        return $this->successResponse($savedTodo->toArray());
    }

    public function update(Request $request, $id)
    {
        $todo = Todo::where('owner_id', $request->authUser->id)->where('id', $id)->first();

        if ($todo) {
            $todo->is_done = $request->input('is_done');
            $todo->save();

            return $this->successResponse($todo->toArray());
        } else {
            return $this->errorResponse(ErrorCode::FORBIDDEN);
        }
    }

    public function destroy(Request $request, $id)
    {
        $todo = Todo::where('owner_id', $request->authUser->id)->where('id', $id)->first();

        if ($todo) {
            Todo::destroy($todo->id);

            return $this->successResponse();
        } else {
            return $this->errorResponse(ErrorCode::FORBIDDEN);
        }
    }
}
