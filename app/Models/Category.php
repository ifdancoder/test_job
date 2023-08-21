<?php

namespace App\Models;

use App\Models\ObjectApp;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    protected $table = 'categories';
    use HasFactory;

    protected $fillable = [
        'title',
        'categories_groups_id',
        'multiple',
        'alias'
    ];

    public function objects() {
        return $this->belongsToMany(ObjectApp::class, 'category_object', 'category_id', 'object_id');
    }
}
