<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Specialist;
use App\Models\User;
use Illuminate\Http\Request;

class SpecialistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Все мастера
        $specialists = Specialist::with('user')->get()->sortBy(fn ($specialist) => $specialist->user->last_name ?? '')->values();
        // передаем в blade шаблон
        return view('admin.specialists.index', compact('specialists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $query = User::query();

        //поиск
        if($request->filled('search')){
            $search = $request->input('search');
            $query->where(function($q) use ($search){
                $q->where('first_name', 'LIKE', "%{$search}%")->orWhere('last_name', 'LIKE', "%{$search}%")->orWhere('phone', 'LIKE', "%{$search}%")->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $existingtSpecialists = Specialist::pluck('user_id', 'id')->toArray();
        $users = $query->WhereNotIn('id', $existingtSpecialists)->paginate(15)->withQueryString();

        return view('admin.specialists.select_user', compact('users'));
    }

    public function build (User $user)
    {
        if (Specialist::where('user_id', $user->id)->exists()) {
            return redirect()->route('admin.specialists.create')->with('error', 'Этот пользователь уже стал специалистом.');
        }

        $levels = Level::all();

        return view('admin.specialists.create', compact('user', 'levels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'user_id' => 'required|exists:users,id|unique:specialists,user_id',
            'level' => 'nullable|string|max:255',
            'experience' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
        ], [
            'user_id.required' => 'Выберите пользователя, который станет специалистом.',
            'user_id.exists' => 'Выбранный пользователь не найден в системе.',
            'user_id.unique' => 'Этот пользователь уже является специалистом салона.',
        ]);


        Specialist::create($validated);


        return redirect()->route('admin.specialists.index')
            ->with('success', 'Специалист успешно добавлен.');
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
    public function edit(Specialist $specialist)
    {
        $levels = Level::select('id', 'name')->get();
        return view('admin.specialists.edit', compact('specialist', 'levels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Specialist $specialist)
    {
        $validated = $request->validate([
            'level_id' => ['required', 'integer', 'exists:levels,id'],
            'experience' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
        ], [
            'level_id.required' => 'Выберите уровень квалификации мастера.',
            'level_id.exists' => 'Выбранный уровень не существует в системе.',
            'experience.max' => 'Поле "Опыт работы" не должно превышать 255 символов.',
        ]);

        $specialist->update($validated);

        return redirect()->route('admin.specialists.index')
            ->with('success', 'Данные мастера успешно обновлены.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Specialist $specialist)
    {
        $specialist->delete();
        return redirect()->route('admin.specialists.index')->with('success', 'Специалист успешно удален.');
    }
}
