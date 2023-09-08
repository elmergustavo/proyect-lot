<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use App\Models\SensorReading;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    protected static ?string $pollingInterval = '5s';

    protected function getTablePollingInterval(): ?string
    {
        return '5s';
    }

    protected function getCards(): array
    {
        return [
            Card::make('Temperatura', SensorReading::count())

                ->descriptionIcon('heroicon-s-trending-up')
                ->chart(SensorReading::pluck('temperature')->toArray())
                ->color('success'),
            Card::make('Humedad', SensorReading::count())

                ->descriptionIcon('heroicon-s-trending-down')
                ->chart(SensorReading::pluck('humidity')->toArray())
                ->color('danger'),

        ];
    }
}
