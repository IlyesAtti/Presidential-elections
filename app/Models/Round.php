<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Round extends Model {
    use HasFactory;

    protected $fillable = ['index'];

    public function candidates() {
        return $this->hasMany(Candidate::class, 'roundIndex', 'index');
    }

    public function votes() {
        return $this->hasMany(Vote::class);
    }
}