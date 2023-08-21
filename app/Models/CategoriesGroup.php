<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoriesGroup extends Model
{
    protected $table = 'categories_groups';
    use HasFactory;

    protected $fillable = [
        'title',
        'multiple'
    ];

    public function categories() {
        return $this->hasMany(Category::class, 'categories_groups_id');
    }
}
