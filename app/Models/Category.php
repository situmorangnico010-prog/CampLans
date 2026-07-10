<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    protected $fillable = ['kode_kategori', 'name'];
    
    protected static function boot() {
        parent::boot();
        static::creating(function ($category) {
            $latest = static::latest('id')->first();
            $num = $latest ? ((int) substr($latest->kode_kategori, 3)) + 1 : 1;
            $category->kode_kategori = 'CAT' . str_pad($num, 3, '0', STR_PAD_LEFT);
        });
    }
    
    public function items() {
        return $this->hasMany(Item::class);
    }
}