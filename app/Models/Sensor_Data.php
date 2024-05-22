<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sensor_Data extends Model
{
    use HasFactory;

    protected $fillable = ['Oxygen_Rate', 'Heart_Rate','clieus',
    'prediction'];

    public function oxygenGenerator()
    {
        return $this->belongsTo(Oxygen_Generator::class);
    }
}
