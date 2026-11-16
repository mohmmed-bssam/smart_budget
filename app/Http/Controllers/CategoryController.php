<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
 public function index()
{
    $itemsPerPage = (int) Setting::where('key', 'items_per_page')
        ->value('value');

    $itemsPerPage = $itemsPerPage ?: 10;

    $categories = Category::where('user_id', Auth::id())
        ->orWhereNull('user_id')
        ->latest()
        ->paginate($itemsPerPage);

    return view('categories.index', compact('categories'));
}

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'icon' => 'nullable|string|max:255',
        ]);

        Category::create([
            'user_id' => Auth::id(),

            'name' => [
                'ar' => $request->name_ar,
                'en' => $request->name_en,
            ],

            'type' => $request->type,
            'icon' => $request->icon,
        ]);

        flash()->success(__('messages.category_created'));

        return redirect()->route('categories.index');
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

        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'icon' => 'nullable|string|max:255',
        ]);

        $category->update([
            'name' => [
                'ar' => $request->name_ar,
                'en' => $request->name_en,
            ],

            'type' => $request->type,
            'icon' => $request->icon,
        ]);

        flash()->success(__('messages.category_updated'));

        return redirect()->route('categories.index');
    }

    public function destroy(Category $category)
    {
        $this->authorizeCategory($category);

        $category->delete();

        flash()->warning(__('messages.category_deleted'));

        return redirect()->route('categories.index');
    }
public function quickStore(Request $request)
{
    $validated = $request->validate([
        'name_ar' => [
            'required',
            'string',
            'max:255',
        ],

        'name_en' => [
            'required',
            'string',
            'max:255',
        ],

        'type' => [
            'required',
            'in:income,expense',
        ],
    ]);


    $category = Category::create([
        'user_id' =>
            Auth::id(),

        'name' => [
            'ar' =>
                $validated['name_ar'],

            'en' =>
                $validated['name_en'],
        ],

        'type' =>
            $validated['type'],
    ]);


    $locale =
        app()->getLocale();


    return response()->json([
        'success' => true,

        'category' => [
            'id' =>
                $category->id,

            'type' =>
                $category->type,

            'name' =>
                $category->name[$locale]
                ?? $category->name['en']
                ?? '',
        ],
    ]);
}
 private function authorizeCategory(Category $category)
{
    if (
        $category->user_id === null
        ||
        $category->user_id !== Auth::id()
    ) {
        abort(403);
    }
}
}
