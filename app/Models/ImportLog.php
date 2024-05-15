<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    use HasFactory;
    protected $table = 'import_logs';
    protected $fillable = [
        'file_name',
        'category_id',
        'items_count',
        'imported_at',
        'status',
        'data'
    ];

    protected $appends = [
        'category_name'
    ];

    public function getDataAttribute()
    {
        return !empty($this->attributes['data']) ? json_decode($this->attributes['data'], true) : [];
    }

    public function getCategoryNameAttribute()
    {
        return Category::query()->find($this->attributes['category_id'])->title;
    }
}
