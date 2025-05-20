<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\auth;
use App\Models\Category;
use App\Models\Todo;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function index()
    {
        $categories = Category::withCount('todos')->where('user_id', auth::id())->get();
        return view('category.index', compact('categories'));
    }

    public function create()
    {
        return view('category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $category = Category::create([
            'title' => ucfirst($request->title),
            'user_id' => auth()->id(),
        ]);
        return redirect()->route('category.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        if (auth()->user()->id == $category->user_id) {
            return view('category.edit', compact('category'));
        } else {
            //abort(403);
            //abort(403, 'Not authorized);
            return redirect()->route('category.index')->with('danger', 'You are not authorized to edit this category!');
        }
    }

    public function update(Request $request, Category $category){
        $request->validate([
            'title' => 'required|max:255',
        ]);
        $category->update([
            'title' => ucfirst($request->title)
        ]);

        return redirect()->route('category.index')->with('success', 'Category updated succesfully!');
    }

        public function destroy(Category $category)
    {
        if (auth()->user()->id == $category->user_id) {
            $category->delete();
            return redirect()->route('category.index')->with('success', 'Category deleted successfully!');
        } else {
            return redirect()->route('category.index')->with('danger', 'You are not authorized to delete this category!');
        }
    }
}
