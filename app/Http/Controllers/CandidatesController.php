<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;
use Illuminate\Support\Facades\Auth;
use App\Models\Round;
use App\Models\Vote;

class CandidatesController extends Controller {
    public function candidate(Request $request) {
        $user = Auth::user();
        $currentRoundIndex = Round::max('index');
        $existingCandidate = Candidate::where('userId', $user->id)
                                      ->where('roundIndex', $currentRoundIndex)
                                      ->first();

        if (!$existingCandidate) {
            Candidate::create([
                'userId' => $user->id,
                'userName' => $user->name,
                'roundIndex' => $currentRoundIndex,
                'votes' => 0,
            ]);
        }
        return redirect()->back()->with('message', "You have successfully submitted your candidacy");
    }

    public function revokeCandidate(Request $request) {
        $user = Auth::user();
        $currentRoundIndex = Round::max('index');
        $candidate = Candidate::where('userId', $user->id)
                                ->where('roundIndex', $currentRoundIndex)
                                ->first();
    
        if ($candidate) {
            $votesForCandidate = Vote::where('candidateId', $candidate->id)
                                        ->where('roundIndex', $currentRoundIndex)
                                        ->get();
    
            foreach ($votesForCandidate as $vote) {
                $vote->delete();
                $votedCandidate = Candidate::find($vote->votedFor);
                if ($votedCandidate) {
                    $votedCandidate->isVoted = false;
                    $votedCandidate->votedFor = 0;
                    $votedCandidate->save();
                }
            }
    
            $candidate->delete();
    
            return redirect()->back()->with('message', 'Your candidacy has been successfully withdrawn.');
        }
    }

    public function show(Request $request) {
        $currentRoundIndex = $request->input('round', Round::max('index'));
        if ($currentRoundIndex === null) {
            $currentRoundIndex = 1;
        }

        $candidates = Candidate::with('user')
                                ->where('roundIndex', $currentRoundIndex)
                                ->orderBy('votes', 'desc')
                                ->get();

        $user = Auth::user();
        $userHasVoted = Vote::where('userId', $user->id)
                            ->where('roundIndex', $currentRoundIndex)
                            ->exists();

        $allRounds = Round::select('index')->orderBy('index')->pluck('index');
        
        $existingCandidate = Candidate::where('userId', $user->id)
                                      ->where('roundIndex', $currentRoundIndex)
                                      ->first();

        $userVotedCandidate = Vote::where('userId', $user->id)
                                  ->where('roundIndex', $currentRoundIndex)
                                  ->first();

        $newRoundIndex = Round::max('index');

        return view('dashboard', [
            'candidates' => $candidates,
            'userHasVoted' => $userHasVoted,
            'currentRoundIndex' => $currentRoundIndex,
            'allRounds' => $allRounds,
            'userVotedCandidate' => $userVotedCandidate,
            'existingCandidate' => $existingCandidate,
            'newRoundIndex' => $newRoundIndex
        ]);
    }

    public function showOnHome(Request $request, $round = null) {
        if ($round) {
            $currentRoundIndex = $round;
        } else {
            $currentRoundIndex = \App\Providers\AppServiceProvider::getCurrentRoundIndex();
        }
    
        $allRounds = Round::select('index')
                          ->orderBy('index')
                          ->pluck('index');
                                
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
