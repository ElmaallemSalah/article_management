<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $fillable = ['name','description', 'category_id','user_id', 'image'];



    public function scopeWithSearch($query, $search)
    {
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhereHas('category', function ($query) use ($search) {
                      $query->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('user', function ($query) use ($search) {
                      $query->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhere('description', 'like', '%' . $search . '%');
        }
        return $query;
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
