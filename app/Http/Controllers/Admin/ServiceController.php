<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::orderBy('name', 'asc')->get();
        return view('admin.services.index', compact('services'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.services.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
           'name' => 'required','string','unique:services,name',
           'description' => ['required','string'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'active' => ['required','boolean'],
        ],
        [
            'name.required' => 'Укажите название услуги.',
            'name.unique' => 'Такая услуга уже существует.',

            'description.required' => 'Укажите описание услуги.',

            'category_id.required' => "Выберите категорию услуги.",
            'category_id.exists' => 'Выбранная категория не существует.'

        ]);
        Service::create($validated);


        return redirect()->route('admin.services.index')->with('success', 'Услуга добавлена.');
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
    public function edit(Service $service)
    {
        $categories = Category::select('id', 'display_name')->get();
        return view('admin.services.edit', compact('service', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required','string','unique:services,name',
            'description' => ['required','string'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'active' => ['required','boolean'],
        ],
        [
            'name.required' => 'Укажите название услуги.',
            'name.unique' => 'Такая услуга уже существует.',

            'description.required' => 'Укажите описание услуги.',

            'category_id.required' => "Выберите категорию услуги.",
            'category_id.exists' => 'Выбранная категория не существует.'

        ]);

        $service->update($validated);

        return redirect()->route('admin.services.index')->with('success', 'Услуга обновлена.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Услуга удалена.');

    }
}
