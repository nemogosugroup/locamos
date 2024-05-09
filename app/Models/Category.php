<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Category extends Model implements TranslatableContract
{
    use Translatable;
    use HasFactory;
    protected $table = 'categories';
    // protected $guarded = [''];
    public $translatedAttributes = ['title'];
    protected $fillable = [
        'slug', 'icon'
    ];
    // protected $appends = [
    //     'translations'
    // ];
    protected static function boot() {
        parent::boot();
        static::creating(function ($category) {
            $slug = Str::slug($category->title);
            $slugCount = static::whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")->count();
            $category->slug = ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;;
        });
    }
    public function postTranslations()
    {
        return $this->hasMany('App\Models\Post');
    }

    // public function getTranslationsAttribute()
    // {
    //     return $this->translationsLanguage()->first();
    // }
}
