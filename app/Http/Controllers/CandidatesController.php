<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CandidatesController extends Controller {
    public function show(Request $request) {
        $currentRoundIndex = $request->input('round', Candidate::max('roundIndex'));
        if ($currentRoundIndex === null) {
            $currentRoundIndex = 1;
        }
    
        $newRoundIndex = Candidate::max('roundIndex');

        $candidates = Candidate::with('user')
                            ->where('roundIndex', $currentRoundIndex)
                            ->get();
    
        $user = Auth::user();
        $userHasVoted = Candidate::where('userId', $user->id)
                                ->where('roundIndex', $currentRoundIndex)
                                ->where('isVoted', true)
                                ->exists();
        $allRounds = Candidate::select('roundIndex')->distinct()->orderBy('roundIndex')->pluck('roundIndex');
    
        return view('dashboard', [
            'candidates' => $candidates,
            'userHasVoted' => $userHasVoted,
            'currentRoundIndex' => $currentRoundIndex,
            'newRoundIndex' => $newRoundIndex,
            'allRounds' => $allRounds
        ]);
    }

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
                $userWhoVote->isVoted = true;
                $userWhoVote->save();

                return redirect()->back();
            }
        } else {
            return redirect()->back();
        }
    }

    public function nextRound(Request $req) {
        $currentRoundIndex = Candidate::max('roundIndex');
        $newRoundIndex = $currentRoundIndex + 1;

        $users = \App\Models\User::all();
        foreach ($users as $user) {
            Candidate::create([
                'userId' => $user->id,
                'roundIndex' => $newRoundIndex,
                'votes' => 0,
                'isVoted' => false,
            ]);
        }
        return redirect()->route('dashboard', ['newRoundIndex' => $newRoundIndex]);
    }

    public function showOnHome(Request $request, $round = null) {
        if ($round) {
            $currentRoundIndex = $round;
        } else {
            $currentRoundIndex = \App\Providers\AppServiceProvider::getCurrentRoundIndex();
        }
    
        $allRounds = Candidate::select('roundIndex')
                                ->distinct()->orderBy('roundIndex')
                                ->pluck('roundIndex');
        $candidates = Candidate::with('user')
                                ->where('roundIndex', $currentRoundIndex)
                                ->orderBy('votes', 'desc')
                                ->get();

        return view('welcome', [
            'allRounds' => $allRounds,
            'candidates' => $candidates
        ]);
    }
}

