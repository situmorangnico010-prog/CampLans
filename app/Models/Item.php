<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Item extends Model {
    protected $fillable = ['category_id', 'name', 'daily_rate', 'image_url', 'stock'];
    
    public function category() {
        return $this->belongsTo(Category::class);
    }
    
    public function rentalDetails() {
        return $this->hasMany(RentalDetail::class);
    }
}