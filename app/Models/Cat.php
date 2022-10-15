<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cat extends Model
{
    protected $fillable = ['name','breed','gender','date_of_birth','image','introduction']; //保存したいカラム名が複数の場合
    use HasFactory;

    public function blogs()
    {
        return $this->belongsToMany(Blog::class);
    }

}
