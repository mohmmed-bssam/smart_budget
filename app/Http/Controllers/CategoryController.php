<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::where('user_id', auth()->id())
            ->orWhereNull('user_id')
            ->latest()
            ->paginate(10);


        return view('categories.index', compact('categories'));
    }



    public function create()
    {
        return view('categories.create');
    }



    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => 'required|string|max:255',

            'type' => 'required|in:income,expense',

            'icon' => 'nullable|string',

            'color' => 'nullable|string',

        ]);


        $validated['user_id'] = auth()->id();


        Category::create($validated);


        flash()->success(__('messages.category_created'));


        return redirect()
            ->route('categories.index');
    }




    public function show(Category $category)
    {
        $this->authorizeCategory($category);


        return view('categories.show', compact('category'));
    }




    public function edit(Category $category)
    {
        $this->authorizeCategory($category);


        return view('categories.edit', compact('category'));
    }




    public function update(Request $request, Category $category)
    {
        $this->authorizeCategory($category);


        $validated = $request->validate([

            'name' => 'required|string|max:255',

            'type' => 'required|in:income,expense',

            'icon' => 'nullable|string',

            'color' => 'nullable|string',

        ]);



        $category->update($validated);



        flash()->success(__('messages.category_updated'));



        return redirect()
            ->route('categories.index');
    }




    public function destroy(Category $category)
    {
        $this->authorizeCategory($category);


        $category->delete();



        flash()->warning(__('messages.category_deleted'));



        return redirect()
            ->route('categories.index');
    }




    private function authorizeCategory(Category $category)
    {
        if (
            $category->user_id !== auth()->id()
            && $category->user_id !== null
        ) {
            abort(403);
        }
    }
}