<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Todo;
use JWTAuth;
use App\Http\Controllers\ErrorCode;

class TodoController extends Controller
{

    public function index(Request $request)
    {
        $todos = $request->authUser->todos;
        return $this->successResponse($todos->toArray());
    }

    public function show(Request $request, $id)
    {
        $todo = $request->authUser->todos()->where('id', $id)->first();

        if(count($todo) > 0){
          return $this->successResponse($todo);
        } else {
          return $this->errorResponse(ErrorCode::FORBIDDEN);
        }

    }

    public function store(Request $request)
    {
        $newTodo = new Todo($request->all());

        $savedTodo = $request->authUser->todos()->save($newTodo);
        return $this->successResponse($savedTodo->toArray());
    }

    public function update(Request $request, $id)
    {
        // make sure is_done was sent
        if(!$request->has('is_done')){
          return $this->errorResponse(ErrorCode::BAD_REQUEST);
        }

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
