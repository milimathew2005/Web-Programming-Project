<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display categories list with book counts.
     */
    public function index(): View
    {
        $categories = Category::withCount('books')->orderBy('name')->get();
        return view('librarian.categories.index', compact('categories'));
    }

    /**
     * Store a new category.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        Category::create([
            'name' => $request->name,
        ]);

        return back()->with('success', "Category '{$request->name}' created successfully.");
    }

    /**
     * Update an existing category.
     */
    public function update(StoreCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update([
            'name' => $request->name,
        ]);

        return back()->with('success', 'Category updated successfully.');
    }

    /**
     * Delete category if no books are assigned to it.
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->books()->count() > 0) {
            return back()->with('error', "Cannot delete category '{$category->name}' because books are currently assigned to it.");
        }

        $name = $category->name;
        $category->delete();

        return back()->with('success', "Category '{$name}' deleted successfully.");
    }
}
