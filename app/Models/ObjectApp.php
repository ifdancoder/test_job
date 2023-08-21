<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjectApp extends Model
{
    protected $table = 'objects';
    use HasFactory;

    protected $fillable = [
        'title'
    ];
    public function categories() {
        return $this->belongsToMany(Category::class, 'category_object', 'object_id', 'category_id');
    }
}
