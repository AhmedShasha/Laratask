<?php

namespace App\Models\Laratask;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'size', 'type', 'path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id','id');
    }
}
