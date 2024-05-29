<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rols extends Model
{
    use HasFactory;
    protected $table = 'rols';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name_rols',
    ];

    public function user()
    {
        return $this->hasMany(User::class, 'rols_id');
    }
}
