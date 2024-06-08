<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Round;

class RoundController extends Controller {
    public function nextRound(Request $request) {
        $currentRoundIndex = Round::max('index');
        if ($currentRoundIndex === null) {
            $currentRoundIndex = 0;
        }

        $newRoundIndex = $currentRoundIndex + 1;

        Round::create(['index' => $newRoundIndex]);

        return redirect()->route('dashboard', ['round' => $newRoundIndex]);
    }
}
