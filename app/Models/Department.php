<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_name',
    ];

     // Define the primary key as a UUID
     protected $primaryKey = 'id';

     // Set the primary key type to string
     protected $keyType = 'string';
 
     // Disable auto-incrementing of the primary key
     public $incrementing = false;

     // Automatically generate UUID for new records
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }
}
