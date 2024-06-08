<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model {
    use HasFactory;

    protected $fillable = ['userId', 'candidateId', 'roundIndex'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function candidate() {
        return $this->belongsTo(Candidate::class);
    }

    public function round() {
        return $this->belongsTo(Round::class);
    }
}