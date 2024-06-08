<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
            <link rel="stylesheet" href="{{url('CSS/custom.css')}}"/>
        </h2>
    </x-slot>
    <div class="py-12">
        @if(Auth::id() == 1)
            <form action="{{ route('nextRound') }}" method="POST">
                @csrf
                <button type="submit" class="btn-next-round">Next Round</button>
            </form>
        @endif
        @if($existingCandidate)
            <form action="/dashboard/revokeCandidate" method="POST">
                @csrf
                <input type="hidden" name="userId" value="{{ Auth::id() }}">
                <button type="submit" class="btn-revoke-candidate">Revoke Candidature</button>
            </form>
        @else
            <form action="/dashboard/candidate" method="POST">
                @csrf
                <input type="hidden" name="userId" value="{{ Auth::id() }}">
                <button type="submit" class="btn-candidate">Candidate</button>
            </form>
        @endif
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2>Current Round: {{ $currentRoundIndex }}</h2>
                    <div class="round-buttons">
                        @foreach($allRounds as $round)
                            <form method="GET" action="{{ route('dashboard') }}" style="display:inline-block;">
                                <input type="hidden" name="round" value="{{ $round }}">
                                <button type="submit" class="btn-round">{{ $round }}</button>
                            </form>
                        @endforeach
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Votes</th>
                                @if($currentRoundIndex == $newRoundIndex)
                                    <th>Vote</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($candidates as $candidate)
                                <tr>
                                    <td>{{ $candidate->user->name }}</td>
                                    <td>{{ $candidate->votes }}</td>
                                    @if($currentRoundIndex == $newRoundIndex)
                                        <td>
                                            @if(Auth::check())
                                                @if($userVotedCandidate && $userVotedCandidate->candidateId == $candidate->id)
                                                    <form action="/dashboard/revokeVote" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $candidate->id }}">
                                                        <button type="submit" class="btn-revoke-vote">Revoke Vote</button>
                                                    </form>
                                                @elseif(!$userHasVoted)
                                                    <form action="/dashboard/vote" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $candidate->id }}">
                                                        <button type="submit" class="btn-vote">Vote</button>
                                                    </form>
                                                @else
                                                    <p style="color:#fff">You have already voted</p>
                                                @endif
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
