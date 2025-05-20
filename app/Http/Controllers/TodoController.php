<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\auth;
use App\Models\Todo;
use App\Models\Category;

class TodoController extends Controller
{
    public function index()
    {
        // $todos = Todo::all();
        $todos = Todo::with('category')->where('user_id', auth()->user()->id)
        ->orderBy('created_at', 'desc')
        ->orderBy('is_done', 'asc')
        ->paginate(10);
        //dd($todos);

        $todosCompleted = Todo::where('user_id', auth()->user()->id)
        ->where('is_done', true)
        ->count();
        return view('todo.index', compact('todos', 'todosCompleted'));
    }

    public function create()
    {
         $categories = Category::where('user_id', auth()->id())->get(); // Ambil kategori milik user
    return view('todo.create', compact('categories'));
    }

    public function edit(Todo $todo)
    {
        if (auth()->user()->id == $todo->user_id) {
            //dd($todo);
            return view('todo.edit', compact('todo'));
        } else {
            //abort(403);
            //abort(403, 'Not authorized);
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to edit this todo!');
        }
        
    }

    public function update(Request $request, Todo $todo) 
    {
        $request->validate([
            'title' => 'required|max:255',
        ]);

        //practical
        // $todo->title = $request->title;
        // $todo->save();

        // Eloquent way - Readable
        $todo->update([
            'title' => ucfirst($request->title)
        ]);

        return redirect()->route('todo.index')->with('success', 'Todo updated succesfully!');
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        $todo = Todo::create([
            'title' => ucfirst($request->title),
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
        ]);
        return redirect()->route('todo.index')->with('success', 'Todo created successfully.');
    }

    public function complete(Todo $todo)
    {
        if (auth()->user()->id == $todo->user_id) {
            $todo->update(['is_done' => true,
        ]);
            return redirect()->route('todo.index')->with('success', 'Todo completed successfully.');
        } else {
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to complete this todo!');
        }
    }

    public function uncomplete(Todo $todo)
    {
        if (auth()->user()->id == $todo->user_id) {
            $todo->update(['is_done' => false,
        ]);
            return redirect()->route('todo.index')->with('success', 'Todo uncompleted successfully.');
        } else {
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to uncomplete this todo!');
        }
    }

    public function destroy(Todo $todo)
    {
        if (auth()->user()->id == $todo->user_id) {
            $todo->delete();
            return redirect()->route('todo.index')->with('success', 'Todo deleted successfully!');
        } else {
            return redirect()->route('todo.index')->with('danger', 'You are not authorized to delete this todo!');
        }
    }

    public function destroyCompleted()
    {
        // get all todos for current user where is_done is true
        $todosCompleted = Todo::where('user_id', auth()->user()->id)
    ->where('is_done', true)
    ->get();
    foreach ($todosCompleted as $todo) {
        $todo->delete();
    }

    //dd($todosCompleted);
    return redirect()->route('todo.index')->with('success', 'All completed todos deleted successfully!');
    }

}