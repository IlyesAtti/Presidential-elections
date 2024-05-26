<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
            <link rel="stylesheet" href="{{url('CSS/custom.css')}}"/>
        </h2>
    </x-slot>
    <div class="py-12">
        @if(Auth::id() == 1)
            <form method="POST" action="/next-round">
                @csrf
                <button type="submit" class="btn-next-round">Next Round</button>
            </form>
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
                                            @if(Auth::check() && !$userHasVoted)
                                                <form action="/dashboard/vote" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="userId" value="{{ Auth::id() }}">
                                                    <input type="hidden" name="id" value="{{ $candidate->id }}">
                                                    <input type="hidden"  name="roundIndex" value="{{ $candidate->roundIndex }}">
                                                    <button type="submit" class="btn-vote">Vote</button>
                                                </form>
                                            @else 
                                                <p style="color:#fff">You have already voted</p>
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
