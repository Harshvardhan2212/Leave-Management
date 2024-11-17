<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Employee extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'department_id',
        'designation',
        'joining_date',
        'current_salary',
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

    public function userDetails()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function departmentDetails()
    {
        return $this->belongsTo(Department::class,'department_id','id');
    }

    public function salaryDetails(){
        return $this->hasMany(Salary::class,'employee_id','id');
    }    

    public function leave(){
        return $this->hasMany(Leave::class,'employee_id','id');
    }
}
