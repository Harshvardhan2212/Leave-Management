<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'basic_salary',
        'allowances',
        'deductions',
        'net_salary',
        'payment_date',
        'payment_status',
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
            // Generate UUID for the primary key
            $model->id = (string) Str::uuid();

            // Calculate net salary (basic_salary + allowances - deductions)
            $model->net_salary = $model->basic_salary + $model->allowances - $model->deductions;
        });

        static::updating(function ($model) {
            // Recalculate net salary on update
            $model->net_salary = $model->basic_salary + $model->allowances - $model->deductions;
        });
    }

    public function employee(){
        return $this->belongsTo(Employee::class,'employee_id','id');
    }
}
