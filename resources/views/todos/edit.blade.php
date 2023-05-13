@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Todos App</div>

                <div class="card-body">
                            <h4>Edit Form</h4>
                <!-- added code -->
                <form method="post" action="{{route('todos.update')}}">
                @csrf    
                @method('PUT')
                    <input type="hidden" name="todo_id" value="{{$todo->id}}">

                <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control"value="{{$todo->title}}">
    
              </div>
              <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" cols="5" rows="5">{{ trim($todo->description) }}</textarea>
          </div>
             <div class="mb-3">
                <label for="">Status</label>
                <select name="is_completed" class="form-control">
                    <option value="0" selected disabled hidden>Not Started</option>
                    <option value="1">In progress</option>
                    <option value="2">Completed</option>
                </select>
            </div>
         <button type="submit" class="btn btn-primary">Update</button>
         <a href="{{ url()->previous() }}" class="btn btn-danger">Cancel</a>

        </form>   
<!-- till here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection