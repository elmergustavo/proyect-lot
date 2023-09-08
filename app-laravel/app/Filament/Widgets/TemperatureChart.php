<?php

namespace App\Filament\Widgets;

use Filament\Widgets\LineChartWidget;
use App\Models\SensorReading;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
// use App\Mail\Sensor;
use App\Models\User;

use App\Notifications\Sensor;


class TemperatureChart extends LineChartWidget

{
    protected static ?string $heading = 'Grafica temperatura';
    protected int | string | array $columnSpan = 'full';

    protected static ?string $pollingInterval = '5s';

    protected function getTablePollingInterval(): ?string
    {
        return '5s';
    }

    protected static ?int $sort = 1;

    protected function getData(): array
    {
        // $this->enviarCorreo();
        $user = User::find(1);
        // $user = User::find(1);


        $ultimaTemperatura = SensorReading::latest()->first();


        if ($ultimaTemperatura->temperature > 21) {
            // Envía un correo electrónico
            // $user = User::find(1);
            $user->notify(new Sensor($ultimaTemperatura->temperature));

            // Mail::to('destinatario@example.com')->send(new TemperaturaSuperada($ultimaTemperatura));
        }


        // $user->notify(new Sensor());




        // Notification::send($user, new Sensor());
        return [
            'datasets' => [
                [
                    'label' => 'Temperatura',
                    'data' => SensorReading::pluck('temperature')->toArray(),
                ],
            ],
            'labels' => SensorReading::selectRaw("DATE_FORMAT(created_at, '%H:%i') as hora")->get()->pluck('hora')->toArray(),
        ];
    }


    // public function enviarCorreo()
    // {
    //     $correo = new Sensor();

    //     Mail::to('destinatario@example.com')->send($correo);

    //     return 'Correo enviado con éxito';
    // }
}
