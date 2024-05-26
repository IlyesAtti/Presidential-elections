<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Candidate extends Model {
    use HasFactory;

    protected $fillable = [
        'userId',
        'roundIndex',
        'isVoted',
        'votes'
    ];

    protected $hidden = [
        'userId'
    ];
    public function user() {
        return $this->belongsTo(User::class, 'userId');
    }
}

$candidates = Candidate::with('user')->get();
