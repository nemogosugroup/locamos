<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use Translatable;
    use HasFactory;
    protected $table = 'posts';
    public $translatedAttributes = ['title', 'description', 'content'];
    protected $fillable = [
        'slug',
        'category_id',
        'manager',
        'phone_number',
        'lat',
        'long',
        'feature_image',
        'images',
        'import_log_id'
    ];
    protected $appends = [
        'lng'
    ];

    protected static function boot() {
        parent::boot();
        static::creating(function ($post) {
            $slug = Str::slug($post->title);
            $slugCount = static::whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")->count();
            $post->slug = ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;;
        });
    }
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\Category');
    }
    public function log(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo('App\Models\ImportLog');
    }

    public function getFeatureImageAttribute(): string
    {
        return !empty($this->attributes['feature_image']) ? $this->attributes['feature_image'] : '/default/default_image.jpg';
    }

    public function getImagesAttribute(): ?array
    {
        return $this->attributes['images'] ? json_decode($this->attributes['images'], true) : [];
    }

    public function getLngAttribute()
    {
        return $this->attributes['long'];
    }
}
