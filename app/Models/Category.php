<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'title',
        'user_id',

    ];


    //relasi satu kategori banyak todo
    public function todos()
    {
        return $this->hasMany(Todo::class);
    }

    //hitung jumlah todo untuk kategori
    public function getTodoCountAttribute()
    {
        return $this->todos()->count();
    }
}
