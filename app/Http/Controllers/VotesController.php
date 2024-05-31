<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;
use Illuminate\Support\Facades\Auth;

class VotesController extends Controller {
    public function vote(Request $req) {
        $candidate = Candidate::find($req->id);
        if ($candidate && Auth::check()) {
            $userId = $req->userId;
            $roundIndex = $candidate->roundIndex;
            $userWhoVote = Candidate::where('userId', $userId)
                                    ->where('roundIndex', $roundIndex)
                                    ->first();

            if ($userWhoVote && !$userWhoVote->isVoted) {
                $candidate->votes += 1;
                $candidate->save();
                $userWhoVote->votedFor = $candidate->userId;
                $userWhoVote->isVoted = true;
                $userWhoVote->save();

                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }

    public function revokeVote(Request $req) {
        $candidate = Candidate::find($req->id);
        if ($candidate && Auth::check()) {
            $userId = Auth::id();
            $roundIndex = $candidate->roundIndex;
            $userWhoVote = Candidate::where('userId', $userId)
                                    ->where('roundIndex', $roundIndex)
                                    ->first();

            if ($userWhoVote && $userWhoVote->isVoted && $userWhoVote->votedFor == $candidate->userId) {
                $candidate->votes -= 1;
                $candidate->save();
                $userWhoVote->votedFor = 0;
                $userWhoVote->isVoted = false;
                $userWhoVote->save();

                return redirect()->back()->with('message', 'You have successfully revoked your vote.');
            } 
        } else {
            return redirect()->back();
        }
    }
}
