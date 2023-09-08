<?php

namespace App\Filament\Widgets;

use Closure;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

use App\Models\SensorReading;

class LatestSensorReading extends BaseWidget
{

    protected static ?int $sort = 3;
    protected static ?string $pollingInterval = '5s';
    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return SensorReading::query()->latest();
    }

    protected function getTablePollingInterval(): ?string
    {
        return '5s';
    }


    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('TemperatureFormat')
                ->label('Humedad'),
            Tables\Columns\TextColumn::make('HumidityFormat')
                ->label('Humedad'),
            Tables\Columns\TextColumn::make('updated_at')
                ->label('Fecha')->date('d/m/Y'),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Hora')->dateTime('H:i:s'),
        ];
    }
}
