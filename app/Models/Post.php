<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;
    protected $table = 'posts';
    protected $fillable = [
        'title', 
        'slug', 
        'category_id',
        'manager',
        'lat',
        'long',
        'description',
        'content',
    ];
    protected static function boot() {
        parent::boot();
        static::creating(function ($post) {
            $slug = Str::slug($post->title);
            $slugCount = static::whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")->count();
            $post->slug = ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;;
        });
    }
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
}
