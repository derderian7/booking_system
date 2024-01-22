<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table= 'services';

    protected $fillable=[
        'business_id',
        'description',
        'price',
        'name'
    ];

    public function business(){
        return $this->belongsTo(Business::class);
    }
}
