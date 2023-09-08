<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorReading extends Model
{
    use HasFactory;
    protected $connection = 'accounts';
    protected $table = 'sensor_readings';

    protected $fillable = [
        'temperature',
        'humidity',
        'created_at',
        'updated_at',
    ];

    public function getTemperatureFormatAttribute()
    {
        return $this->temperature . ' °C';
    }

    public function getHumidityFormatAttribute()
    {
        return $this->humidity . ' %';
    }
}
