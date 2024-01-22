<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $table= 'business';

    protected $fillable=[
        'user_id',
        'status',
        'opening_hours',
        'name'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}