<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Observers\CandidateObserver;
use App\Models\User;
use App\Models\Candidate;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\CandidatesController;

class AppServiceProvider extends ServiceProvider {
    public static $currentRoundIndex = 1;
    /**
     * Register any application services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        User::observe(CandidateObserver::class);

        self::$currentRoundIndex = Candidate::max('roundIndex') ?? 1;
    }
    
    public static function getCurrentRoundIndex() {
        return self::$currentRoundIndex;
    }

    public static function setCurrentRoundIndex($roundIndex) {
        self::$currentRoundIndex = $roundIndex;
    }
}
