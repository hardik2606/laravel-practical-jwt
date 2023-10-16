<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAddresses extends Model
{
    use HasFactory;
    protected $table = 'employee_addresses';
    protected $fillable = ['e_id','address'];
}
