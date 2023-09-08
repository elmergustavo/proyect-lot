<?php

namespace App\Filament\Widgets;

use Filament\Widgets\LineChartWidget;
use App\Models\SensorReading;


class HumidityChart extends LineChartWidget
{
    protected static ?string $heading = 'Grafica de humedad';

    protected static ?string $pollingInterval = '5s';
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Humedad',
                    'data' => SensorReading::pluck('humidity')->toArray(),
                ],
            ],
            'labels' => SensorReading::selectRaw("DATE_FORMAT(created_at, '%H:%i:%s') as hora")->get()->pluck('hora')->toArray(),
        ];
    }
}
