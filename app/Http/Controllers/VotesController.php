<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\Round;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;

class VotesController extends Controller {
    public function vote(Request $req) {
        $userId = Auth::id();
        $candidate = Candidate::find($req->id);
        if ($candidate && Auth::check()) {
            $userId = Auth::id();
            $roundIndex = $candidate->roundIndex;
            $round = Round::where('index', $roundIndex)->first();
    
            $existingVote = Vote::where('userId', $userId)
                                ->where('roundIndex', $round->id)
                                ->first();
    
            if (!$existingVote) {
                $candidate->votes += 1;
                $candidate->save();
    
                Vote::create([
                    'userId' => $userId, 
                    'candidateId' => $candidate->id,
                    'roundIndex' => $round->id,
                ]);
            }
        }
        return redirect()->back();
    }

    public function revokeVote(Request $req) {
        $candidate = Candidate::find($req->id);
        if ($candidate && Auth::check()) {
            $userId = Auth::id();
            $roundIndex = $candidate->roundIndex;
            $round = Round::where('index', $roundIndex)->first();

            $existingVote = Vote::where('userId', $userId)
                                ->where('roundIndex', $round->index)
                                ->where('candidateId', $candidate->id)
                                ->first();

            if ($existingVote) {
                $candidate->votes -= 1;
                $candidate->save();

                $existingVote->delete();
            }
        }
        return redirect()->back(); 
    }
}
