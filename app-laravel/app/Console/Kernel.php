<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\SensorReading;
use Illuminate\Support\Facades\Notification;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            // Aquí puedes consultar la base de datos para obtener la última temperatura registrada
            $ultimaTemperatura = SensorReading::latest()->first();

            // Verifica si la temperatura supera los 20 grados Celsius
            if ($ultimaTemperatura->temperature > 10) {
                // Envía un correo electrónico
                $user = User::find(1);
                $user->notify(new Sensor($ultimaTemperatura->temperature));

                // Mail::to('destinatario@example.com')->send(new TemperaturaSuperada($ultimaTemperatura));
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
