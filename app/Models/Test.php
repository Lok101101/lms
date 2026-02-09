<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PassedTest;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'content',
        'max_score'
    ];

    public function passesTests() {
        return $this->hasMany(PassedTest::class);
    }
}
