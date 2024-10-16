<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    // Tên bảng nếu không theo chuẩn "payments"
    protected $table = 'payments';

    // Các cột có thể điền dữ liệu (fillable)
    protected $fillable = [
        'user_id', 
        'motelroom_id',
        'amount', 
        'status', 
        'payment_method', 
        'transaction_id'
    ];

    // Thiết lập quan hệ với model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function motelroom()
    {
        return $this->belongsTo(Motelroom::class);
    }
}
