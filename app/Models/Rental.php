<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model {
    protected $fillable = [
        'customer_id', 'start_date', 'end_date', 'total_amount',
        'status', 'actual_return_date', 'late_fee', 'payment_status'
    ];
    
    public function customer() {
        return $this->belongsTo(User::class, 'customer_id');
    }
    
    public function details() {
        return $this->hasMany(RentalDetail::class);
    }
}