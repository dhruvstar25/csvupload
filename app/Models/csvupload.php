<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class csvupload extends Model
{
    use HasFactory;
   protected $fillable =["filepath", "email"];
}
