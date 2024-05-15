<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lawyers extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'contact', 'specializations', 'location', 'experience', 'verified'];

}
