<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
    	'title',
		'introduction',
		'subtitle',
		'content',
		'created_by',
		'updated_by'
    ];

    // Se obtiene el usuario que creó el Post
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Se obtiene el usuario que actualizó el Post
    public function updator()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
