<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $fillable = [
        'first_last_name',
        'second_last_name',
        'first_name',
        'other_names',
        'country',
        'id_type',
        'id_number',
        'email',
        'entry_date',
        'area',
        'status'
    ];
}