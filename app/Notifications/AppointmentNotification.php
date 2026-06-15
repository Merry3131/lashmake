<?php

namespace App\Notifications;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $appointment;
    protected $type;

    public function __construct(Appointment $appointment, string $type)
    {
        $this->appointment = $appointment;
        $this->type = $type;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        // Жадная загрузка связей, чтобы не было ошибок пустого объекта
        $this->appointment->load(['service', 'specialist.user']);

        $serviceName = mb_strtolower($this->appointment->service->name ?? 'услугу');
        $masterName = $this->appointment->specialist->user->first_name ?? 'мастеру';

        // Безопасное получение даты и времени через Carbon
        $datetime = Carbon::parse($this->appointment->appointment_at);
        $date = $datetime->format('d.m.Y');
        $time = $datetime->format('H:i');

        if ($this->type === 'created') {
            return [
                'type' => 'created',
                'title' => 'Новая запись',
                'message' => "Вы успешно запись на услугу «{$serviceName}» к мастеру {$masterName} на {$date} в {$time}. За день до вашей записи, вам позвонит наш администратор для подтверждения вашего прихода.",
                'appointment_id' => $this->appointment->id,
            ];
        }

        if ($this->type === 'confirmed') {
            return [
                'type' => 'confirmed',
                'title' => 'Запись подтверждена',
                'message' => "Ваша запись на услугу «{$serviceName}» ({$date} в {$time}) успешно подтверждена. Ждем вас в нашем салоне!",
                'appointment_id' => $this->appointment->id,
            ];
        }

        return [];
    }
}
