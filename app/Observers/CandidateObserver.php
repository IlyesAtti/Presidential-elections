<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Candidate;
use App\Providers\AppServiceProvider;

class CandidateObserver {

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void {
        $currentRoundIndex = AppServiceProvider::getCurrentRoundIndex();
        $existingCandidate = Candidate::where('userId', $user->id)
                                        ->where('roundIndex', $currentRoundIndex)
                                        ->first();
    
        if (!$existingCandidate) {
            Candidate::create([
                'userId' => $user->id,
                'roundIndex' =>$currentRoundIndex,
                'votes' => 0,
                'isVoted' => false,
            ]);
        }
    }


    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
