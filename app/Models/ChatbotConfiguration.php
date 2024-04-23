<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotConfiguration extends Model
{
    use HasFactory;

    protected $fillable = [
        'temperature',
        'system_instruction',
        'model_name',
        'model_details',
        'txt_file',
        'message1',
        'message2',
        'message3',
        'message4',

    ];
}
