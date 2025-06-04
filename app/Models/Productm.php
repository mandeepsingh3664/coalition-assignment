<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productm extends Model
{
    protected $fillable = ['name', 'quantity', 'price', 'created_at'];

    public static function saveToJson($data)
    {
        $file = public_path('inventory.json');
        $existingData = json_decode(file_get_contents($file), true) ?: [];
        $existingData[] = $data;
        file_put_contents($file, json_encode($existingData, JSON_PRETTY_PRINT));
    }
    
    public static function getAllFromJson()
    {
        $file = public_path('inventory.json');
        return json_decode(file_get_contents($file), true) ?: [];
    }
}
