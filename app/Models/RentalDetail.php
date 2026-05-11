<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RentalDetail extends Model {
    protected $fillable = ['rental_id', 'item_id', 'quantity', 'subtotal'];
    
    public function rental() {
        return $this->belongsTo(Rental::class);
    }
    
    public function item() {
        return $this->belongsTo(Item::class);
    }
}