@extends('layouts.admin')

@section('content')

<style>
    .quiz-stat-card {
        padding: 18px;
        border-radius: 6px;
        color: #fff;
        margin-bottom: 15px;
    }
    .quiz-stat-card h3 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
    }
    .quiz-stat-card p {
        margin: 5px 0 0;
        font-size: 14px;
    }
    .badge-right {
        background: #28a745;
        color: #fff;
        padding: 5px 10px;
        border-radius: 3px;
    }
    .badge-wrong {
        background: #dc3545;
        color: #fff;
        padding: 5px 10px;
        border-radius: 3px;
    }
</style>

<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">Post Quiz Participants</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    <li>
                        <span>{{ $quiz->post->title ?? 'Quiz Result' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    @include('includes.admin.form-both')

    <div class="row">
        <div class="col-lg-12">
            <div class="add-product-content">
                <div class="product-description">
                    <div class="body-area">
                        <h4 class="heading">{{ $quiz->question }}</h4>

                        <p>
                            <strong>Correct Answer:</strong>
                            {{ $quiz->{'option_'.$quiz->correct_answer} ?? 'N/A' }}
                        </p>

                        <div class="row">
                            <div class="col-lg-4">
                                <div class="quiz-stat-card" style="background:#007bff;">
                                    <h3>{{ $totalParticipants }}</h3>
                                    <p>Total Participants</p>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="quiz-stat-card" style="background:#28a745;">
                                    <h3>{{ $rightCount }}</h3>
                                    <p>Right Answers</p>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="quiz-stat-card" style="background:#dc3545;">
                                    <h3>{{ $wrongCount }}</h3>
                                    <p>Wrong Answers</p>
                                </div>
                            </div>
                        </div>

                        @php
                            $weeklyPrizes = $quizPrizeAmounts;
                            $weeklyWinnerByUser = $weeklyWinners->keyBy(function ($winner) {
                                return $winner->answer->user_id ?? null;
                            });
                        @endphp

                        <div class="mt-4 mb-4" style="border:1px solid #ddd; border-radius:6px; padding:15px; background:#fff;">
                            <div class="d-flex justify-content-between align-items-center" style="gap:10px; flex-wrap:wrap;">
                                <div>
                                    <h4 class="heading" style="margin-bottom:5px;">Last 7 Days Participant Ranking</h4>
                                    <span class="text-muted">
                                        {{ $weeklyStart->format('d M Y') }} - {{ $weeklyEnd->format('d M Y') }}
                                    </span>
                                </div>

                                @if($weeklyWinners->count() == 0)
                                    <form action="{{ route('post.quiz.weekly.draw.winners') }}"
                                          method="POST"
                                          onsubmit="return confirm('Auto draw weekly winners from the last 7 days ranking?');">
                                        @csrf
                                        <button type="submit"
                                                class="btn btn-success btn-sm"
                                                {{ $weeklyParticipants->where('correct_count', '>', 0)->count() < 3 ? 'disabled' : '' }}>
                                            Auto Draw 1st, 2nd, 3rd
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="btn btn-secondary btn-sm" disabled>
                                        Weekly Winners Selected
                                    </button>
                                @endif
                            </div>

                            @if($weeklyWinners->count() > 0)
                                <div class="row text-center mt-3">
                                    @foreach($weeklyWinners as $winner)
                                        <div class="col-md-4 mb-2">
                                            <div style="border:1px solid #ddd;border-radius:6px;padding:10px;background:#f8f9fa;">
                                                <strong>
                                                    {{ $winner->position }}{{ $winner->position == 1 ? 'st' : ($winner->position == 2 ? 'nd' : 'rd') }} Winner
                                                </strong>
                                                <br>
                                                {{ $winner->answer->name ?? $winner->answer->user->name ?? 'N/A' }}
                                                @if(!empty($winner->answer->phone))
                                                    <br><small>{{ $winner->answer->phone }}</small>
                                                @endif
                                                <br>
                                                <span class="badge badge-success">
                                                    {{ $weeklyPrizes[$winner->position] ?? 0 }} Tk
                                                </span>
                                                <form action="{{ route('post.quiz.winner.remove', $winner->id) }}"
                                                      method="POST"
                                                      style="display:inline-block; margin-top:6px;"
                                                      onsubmit="return confirm('Remove this weekly winner?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        Remove
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="table-responsive mt-3" style="max-height:360px; overflow-y:auto;">
                                <table class="table table-bordered table-striped text-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Participated</th>
                                            <th>Right</th>
                                            <th>Wrong</th>
                                            <th>Current Winner</th>
                                            <th>Manual Selection</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($weeklyParticipants as $key => $participant)
                                            @php
                                                $participantWinner = $weeklyWinnerByUser->get($participant->user_id);
                                            @endphp
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $participant->name ?? 'N/A' }}</td>
                                                <td>{{ $participant->phone ?? 'N/A' }}</td>
                                                <td>{{ $participant->email ?? 'N/A' }}</td>
                                                <td>{{ $participant->participation_count }}</td>
                                                <td>{{ $participant->correct_count }}</td>
                                                <td>{{ $participant->wrong_count }}</td>
                                                <td>
                                                    @if($participantWinner)
                                                        <span class="badge badge-warning">
                                                            {{ $participantWinner->position }}{{ $participantWinner->position == 1 ? 'st' : ($participantWinner->position == 2 ? 'nd' : 'rd') }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <form action="{{ route('post.quiz.weekly.choose.winner', $participant->user_id) }}"
                                                          method="POST"
                                                          class="d-flex justify-content-center align-items-center"
                                                          style="gap:6px; flex-wrap:wrap;"
                                                          onsubmit="return confirm('Select this participant as weekly winner? Existing winner for this position will be replaced.');">
                                                        @csrf
                                                        <select name="position" class="form-control form-control-sm" style="width:90px;" required>
                                                            <option value="">Rank</option>
                                                            <option value="1">1st</option>
                                                            <option value="2">2nd</option>
                                                            <option value="3">3rd</option>
                                                        </select>
                                                        <button type="submit"
                                                                class="btn btn-info btn-sm"
                                                                {{ $participant->correct_count < 1 ? 'disabled' : '' }}>
                                                            Select
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">No participants found in the last 7 days.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <hr>

                        <h4 class="heading">All Participants</h4>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Selected Answer</th>
                                        <th>Correct Answer</th>
                                        <th>Status</th>
                                        <th>Submitted At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($answers as $key => $answer)
                                        <tr>
                                            <td>{{ $answers->firstItem() + $key }}</td>
                                            <td>{{ $answer->name ?? 'N/A' }}</td>
                                            <td>{{ $answer->phone ?? 'N/A' }}</td>
                                            <td>{{ $answer->email ?? 'N/A' }}</td>
                                            <td>
                                                {{ $quiz->{'option_'.$answer->selected_answer} ?? 'N/A' }}
                                            </td>
                                            <td>
                                                {{ $quiz->{'option_'.$quiz->correct_answer} ?? 'N/A' }}
                                            </td>
                                            <td>
                                                @if($answer->is_correct)
                                                    <span class="badge-right">Right</span>
                                                @else
                                                    <span class="badge-wrong">Wrong</span>
                                                @endif
                                            </td>
                                            <td>{{ $answer->created_at ? $answer->created_at->format('d M Y, h:i A') : 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No participants found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{ $answers->links() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
