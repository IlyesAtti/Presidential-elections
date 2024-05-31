<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CandidatesController extends Controller {
    public function candidate(Request $request) {
        $user = Auth::user();
        $currentRoundIndex = Candidate::max('roundIndex');
            if (!$currentRoundIndex) {
                $currentRoundIndex = 1;
            }
        $existingCandidate = Candidate::where('userId', $user->id)
                                        ->where('roundIndex', $currentRoundIndex)
                                        ->first();
    
        if (!$existingCandidate) {
            Candidate::create([
                'userId' => $user->id,
                'userName' => $user->name,
                'roundIndex' => $currentRoundIndex,
                'votes' => 0,
                'isVoted' => false,
                'votedFor' => 0,
            ]);
        }
    
        return redirect()->back()->with('message', 'Your candidacy has been successfully submitted.');
    }

    public function revokeCandidate(Request $request) {
        $user = Auth::user();
        $currentRoundIndex = Candidate::max('roundIndex');
        $candidate = Candidate::where('userId', $user->id)
                                ->where('roundIndex', $currentRoundIndex)
                                ->first();
    
        if ($candidate) {
            $candidate->delete();
            Candidate::where('roundIndex', $currentRoundIndex)
                        ->where('votedFor', $candidate->userId)
                        ->update(['votedFor' => 0, 'isVoted' => false]);
    
            return redirect()->back()->with('message', 'You have successfully revoke your candidated.');
        } else {
            return redirect()->back();
        }
    }

    public function show(Request $request) {
        $currentRoundIndex = $request->input('round', Candidate::max('roundIndex'));
        if ($currentRoundIndex === null) {
            $currentRoundIndex = 1;
        }
    
        $newRoundIndex = Candidate::max('roundIndex');
        if (!$newRoundIndex) {
            $newRoundIndex = 1;
        }
    
        $candidates = Candidate::with('user')
                            ->where('userId', '!=', 1)
                            ->where('roundIndex', $currentRoundIndex)
                            ->get();
    
        $user = Auth::user();
        $userHasVoted = Candidate::where('userId', $user->id)
                                ->where('roundIndex', $currentRoundIndex)
                                ->where('isVoted', true)
                                ->exists();
    
        $allRounds = Candidate::select('roundIndex')->distinct()->orderBy('roundIndex')->pluck('roundIndex');
        
        $existingCandidate = Candidate::where('userId', $user->id)
                                    ->where('roundIndex', $currentRoundIndex)
                                    ->first();

        $userVotedCandidate = Candidate::where('userId', $user->id)
                                    ->where('roundIndex', $currentRoundIndex)
                                    ->first();
    
        return view('dashboard', [
            'candidates' => $candidates,
            'userHasVoted' => $userHasVoted,
            'currentRoundIndex' => $currentRoundIndex,
            'newRoundIndex' => $newRoundIndex,
            'allRounds' => $allRounds,
            'userVotedCandidate' => $userVotedCandidate,
            'existingCandidate' => $existingCandidate
        ]);
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
                                ->where('userId', '!=', 1)
                                ->where('roundIndex', $currentRoundIndex)
                                ->orderBy('votes', 'desc')
                                ->get();

        return view('welcome', [
            'allRounds' => $allRounds,
            'candidates' => $candidates
        ]);
    }
}

