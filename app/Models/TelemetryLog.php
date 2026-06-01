<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelemetryLog extends Model
{
    protected $fillable = ['sensor_type', 'value', 'unit'];
}