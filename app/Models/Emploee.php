<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emploee extends Model
{
    use HasFactory;
    protected $fillable = [
        'emp_code',
        'first_name',
        'last_name',
        'full_name',
        'joining_date',
        'profile_image'
    ];
}
