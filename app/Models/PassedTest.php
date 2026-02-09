<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassedTest extends Model
{
    use HasFactory;

    protected $table = 'passes_tests';

    protected $fillable = [
        'user_id',
        'test_id',
        'estimation',
        'score'
    ];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}
