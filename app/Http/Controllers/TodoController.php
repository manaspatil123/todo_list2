<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Http\Requests\TodoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $todos = Todo::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        return view('todos.index', [
            'todos' => $todos
        ]);
    }
    public function create()
    {
        return view('todos.create');
    }
    public function store(TodoRequest $request)
    {
        Todo::create([
            'title'=> $request-> title,
            'description'=> $request->description,
            'is_completed'=>0
        ]);
        $todo=new Todo;
        $todo->title = $request->input('title');
        $todo->description = $request->input('description');

        if($request->has('completed')){
            $todo->completed = true;
        }

        $todo->user_id = Auth::user()->id;

        $todo->save();
        $request->session()->flash('alert-success','Todo Created Successfully');
        return to_route('todos.index');
        //  return $request->all();
    }

    public function show($id)
    {
        // $todo = Todo::find($id);
        $todo = Todo::where('id', $id)->where('user_id', Auth::user()->id)->firstOrFail();
        if(!$todo){

            request()->session()->flash('error','Unable to locate Todo');
            return to_route('todos.index')->withErrors([
                'error'=> 'Unable to locate the Todo'
            ]);
        }
        return view('todos.show',['todo'=>$todo]);
    }
    public function edit($id)
    {
        $todo = Todo::where('id', $id)->where('user_id', Auth::user()->id)->firstOrFail();

        if(!$todo){

            request()->session()->flash('error','Unable to locate Todo');
            return to_route('todos.index')->withErrors([
                'error'=> 'Unable to locate the Todo'
            ]);
        }
        return view('todos.edit',['todo'=>$todo]);
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'nullable',
        ]);

        $todo = Todo::where('id', $id)->where('user_id', Auth::user()->id)->firstOrFail();
        $todo->title = $request->input('title');
        $todo->description = $request->input('description');

        if($request->has('completed')){
            $todo->completed = true;
        }else{
            $todo->completed = false;
        }

        $todo->save();

     
        $request->session()->flash('alert-info','Todo Updated Successfully');

        return to_route('todos.index');

    }
    public function destroy($id){

        $todo = Todo::where('id', $id)->where('user_id', Auth::user()->id)->firstOrFail();
        if(!$todo){
 
         request()->session()->flash('error','Unable to locate Todo');
         return to_route('todos.index')->withErrors([
             'error'=> 'Unable to locate the Todo'
         ]);
     }  
     $todo->delete();
     $request->session()->flash('alert-success','Todo Deleted Successfully');

        return to_route('todos.index');

    }
}
