<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function index(){
        $reviews = Review::where('is_approved', 1)->with(['user', 'specialist.user', 'appointment.service'])->latest()->get();


        return view('public.reviews', compact('reviews'));
    }

    public function create(Request $request)
    {

        // Проверяем, передан ли ID записи
        $appointmentId = $request->query('appointment');

        if (!$appointmentId) {
            abort(404, 'Запись не найдена');
        }

        // Находим запись текущего пользователя с подгруженным специалистом
        $appointment = Appointment::where('id', $appointmentId)
            ->where('client_id', Auth::id())
            ->with('specialist.user')
            ->firstOrFail();

        // Проверяем, не оставлял ли пользователь отзыв на эту запись ранее
        $alreadyReviewed = Review::where('appointment_id', $appointment->id)->exists();
        if ($alreadyReviewed) {
            return redirect()->route('dashboard')->with('error', 'Вы уже оставляли отзыв к этой записи.');
        }
        return view('public.review-create', compact('appointment'));
    }

    /**
     * Сохранение отзыва в БД
     */
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'rating'         => 'required|integer|min:1|max:5',
            'comment'        => 'nullable|string|max:1000',
        ]);

        $appointment = Appointment::where('id', $request->appointment_id)
            ->where('client_id', Auth::id())
            ->firstOrFail();

        if (Review::where('appointment_id', $appointment->id)->exists()) {
            return redirect()->route('dashboard')->with('error', 'Вы уже оставляли отзыв.');
        }

        Review::create([
            'appointment_id' => $appointment->id,
            'user_id'        => Auth::id(),
            'specialist_id'  => $appointment->specialist_id,
            'rating'         => $request->rating,
            'comment'        => $request->comment,
        ]);

        return redirect()->route('dashboard')->with('success', 'Спасибо! Ваш отзыв успешно сохранен.');
    }
}
