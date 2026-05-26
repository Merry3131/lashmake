<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Все категории
        $categories = Category::orderBy('id', 'desc')->get();
        // передаем в blade шаблон
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'display_name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,display_name',
                // Кастомная проверка через замыкание (Closure)
                function ($attribute, $value, $fail) {
                    // Если исходное имя совпадает со своим slug-вариантом,
                    // значит оно написано латиницей/символами без нормальных слов
                    if ($value === Str::slug($value)) {
                        $fail('Название категории должно быть понятным текстом, а не ссылкой.');
                    }
                },
            ],
            'description' => 'nullable|string',
        ], [
            'display_name.required' => 'Укажите отображаемое название категории.',
            'display_name.unique' => 'Такая категория уже существует.',
        ]);

        if(empty($validated['slug'])){
            $validated['slug'] = Str::slug($validated['display_name']);
        }
        else{
            $validated['slug'] = Str::slug($validated['slug']);
        }

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Категория успешно создана.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {


        $validated = $request->validate([
            'display_name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,display_name',
                // Кастомная проверка через замыкание (Closure)
                function ($attribute, $value, $fail) {
                    // Если исходное имя совпадает со своим slug-вариантом,
                    // значит оно написано латиницей/символами без нормальных слов
                    if ($value === Str::slug($value)) {
                        $fail('Название категории должно быть понятным текстом, а не ссылкой (slug).');
                    }
                },
            ],
            'description' => 'nullable|string',
        ], [
            'display_name.required' => 'Укажите отображаемое название категории.',
            'display_name.unique' => 'Такая категория уже существует.',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['display_name']);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        $category->update($validated);
        return redirect()->route('admin.categories.index')->with('success', '
        Категория успешно обновлена.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Категория успешно удалена.');
    }
}
