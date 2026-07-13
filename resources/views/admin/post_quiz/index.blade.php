@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Post Quiz Results') }}</h4>
                <ul class="links">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li>{{ __('Post Quiz Results') }}</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="add-product-content">
        <div class="product-description">
            <div class="body-area">
                @php
                    $weeklyPrizes = $quizPrizeAmounts;
                    $weeklyParticipantStats = $weeklyParticipants->keyBy('user_id');
                    $weeklyWinnerByUser = $weeklyWinners->keyBy(function ($winner) {
                        return $winner->answer->user_id ?? null;
                    });
                @endphp

                <div class="mb-4" style="border:1px solid #ddd; border-radius:6px; padding:15px; background:#fff;">
                    <div class="d-flex justify-content-between align-items-center" style="gap:10px; flex-wrap:wrap;">
                        <div>
                            <h4 class="heading" style="margin-bottom:5px;">{{ __('Weekly Quiz Winners') }}</h4>
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
                                    {{ __('Auto Draw 1st, 2nd, 3rd') }}
                                </button>
                            </form>
                        @else
                            <button type="button" class="btn btn-secondary btn-sm" disabled>
                                {{ __('Weekly Winners Selected') }}
                            </button>
                        @endif
                    </div>

                    @if($weeklyWinners->count() > 0)
                        <div class="row text-center mt-3">
                            @foreach($weeklyWinners as $winner)
                                @php
                                    $winnerStats = $weeklyParticipantStats->get($winner->answer->user_id ?? null);
                                @endphp
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
                                        <span class="badge badge-primary">
                                            Participated: {{ $winnerStats->participation_count ?? 0 }}
                                        </span>
                                        <span class="badge badge-success">
                                            Right: {{ $winnerStats->correct_count ?? 0 }}
                                        </span>
                                        <br>
                                        <span class="badge badge-warning">
                                            {{ $weeklyPrizes[$winner->position] ?? 0 }} {{ __('Tk') }}
                                        </span>
                                        <form action="{{ route('post.quiz.winner.remove', $winner->id) }}"
                                              method="POST"
                                              style="display:inline-block; margin-top:6px;"
                                              onsubmit="return confirm('Remove this weekly winner?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                {{ __('Remove') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="table-responsive mt-3" style="max-height:260px; overflow-y:auto;">
                        <table class="table table-bordered table-striped text-center mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Participant') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Participated') }}</th>
                                    <th>{{ __('Right') }}</th>
                                    <th>{{ __('Wrong') }}</th>
                                    <th>{{ __('Last Answer') }}</th>
                                    <th>{{ __('Current Winner') }}</th>
                                    <th>{{ __('Manual Selection') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($weeklyParticipants->take(20) as $key => $participant)
                                    @php
                                        $participantWinner = $weeklyWinnerByUser->get($participant->user_id);
                                    @endphp
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $participant->name ?? 'N/A' }}</td>
                                        <td>{{ $participant->phone ?? 'N/A' }}</td>
                                        <td>{{ $participant->participation_count }}</td>
                                        <td>{{ $participant->correct_count }}</td>
                                        <td>{{ $participant->wrong_count }}</td>
                                        <td>
                                            {{ $participant->last_participated_at ? \Carbon\Carbon::parse($participant->last_participated_at)->format('d M Y, h:i A') : 'N/A' }}
                                        </td>
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
                                                    {{ __('Select') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9">{{ __('No participants found in the last 7 days.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="table-responsive" style="max-height: 75vh; overflow-y: auto;">
                    <table class="table table-bordered table-striped text-center align-middle">
                        <thead style="position: sticky; top: 0; background: #fff; z-index: 10;">
                            <tr>
                                <th>{{ __('Post') }}</th>
                                <th>{{ __('Quiz') }}</th>
                                <th>{{ __('Right Answer') }}</th>
                                <th>{{ __('Participants') }}</th>
                                <th>{{ __('Right') }}</th>
                                <th>{{ __('Wrong') }}</th>
                                <th>{{ __('Winners') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $timezone = 'Asia/Dhaka';
                                $todayDate = now($timezone)->toDateString();
                                $yesterdayDate = now($timezone)->subDay()->toDateString();

                                $groupedQuizzes = $quizzes->getCollection()->groupBy(function ($quiz) use ($timezone) {
                                    $date = $quiz->post->schedule_post_date ?? $quiz->post->created_at ?? $quiz->created_at;
                                    return \Carbon\Carbon::parse($date, $timezone)->toDateString();
                                });

                                $prizes = [
                                    1 => '১০০ টাকা ',
                                    2 => '৫০ টাকা ',
                                    3 => '২০ টাকা ',
                                ];
                                $prizes = [
                                    1 => number_format($quizPrizeAmounts[1] ?? 0, 2) . ' Tk',
                                    2 => number_format($quizPrizeAmounts[2] ?? 0, 2) . ' Tk',
                                    3 => number_format($quizPrizeAmounts[3] ?? 0, 2) . ' Tk',
                                ];
                            @endphp

                            @forelse($groupedQuizzes as $date => $dateQuizzes)
                                @php
                                    $dateQuizIds = $dateQuizzes->pluck('id');

                                    $dateWinners = $dayWinners[$date] ?? \App\Models\PostQuizWinner::with(['answer.user'])
                                        ->whereIn('post_quiz_id', $dateQuizIds)
                                        ->where(function ($query) {
                                            $query->whereNull('draw_type')
                                                ->orWhere('draw_type', '!=', 'weekly');
                                        })
                                        ->orderBy('position')
                                        ->get();
                                    $dateTotalParticipants = $dateQuizzes->sum('total_participants');
                                    $dateTotalRight = $dateQuizzes->sum('right_count');
                                    $dateTotalWrong = $dateQuizzes->sum('wrong_count');

                                    $dateCandidates = $dayCandidates[$date] ?? collect();

                                    $dateWinnerCount = $dateWinners->count();
                                @endphp

                                <tr>
                                    <td colspan="8" style="background:#600001;color:#fff;font-weight:700;text-align:left; padding-left:10px; padding-right:10px;">
                                        <div class="d-flex justify-content-between align-items-center" style="gap:10px; flex-wrap:wrap;">
                                            <div>
                                                {{ \Carbon\Carbon::parse($date, $timezone)->format('d M Y') }}
                                            
                                                <span class="badge badge-light" style="margin-left:8px;">
                                                    {{ $dateQuizzes->count() }} {{ __('Quiz') }}
                                                </span>
                                            
                                                <!--<span class="badge badge-info" style="margin-left:8px;">-->
                                                <!--    Participants: {{ $dateTotalParticipants }}-->
                                                <!--</span>-->
                                            
                                                <!--<span class="badge badge-success" style="margin-left:8px;">-->
                                                <!--    Right: {{ $dateTotalRight }}-->
                                                <!--</span>-->
                                            
                                                <!--<span class="badge badge-danger" style="margin-left:8px;">-->
                                                <!--    Wrong: {{ $dateTotalWrong }}-->
                                                <!--</span>-->
                                            
                                                @if($dateWinnerCount > 0)
                                                    <span class="badge badge-warning" style="margin-left:8px;">
                                                        {{ $dateWinnerCount }} Winners Selected
                                                    </span>
                                                @endif
                                            </div>

                                            <!--<div>-->
                                            <!--    @if($date === $yesterdayDate && $dateWinnerCount == 0)-->
                                            <!--        <form action="{{ route('post.quiz.day.draw.winners', $date) }}"-->
                                            <!--              method="POST"-->
                                            <!--              style="display:inline-block;"-->
                                            <!--              onsubmit="return confirm('Draw winners from all correct participants of this date?');">-->
                                            <!--            @csrf-->
                                            <!--            <button type="submit" class="btn btn-sm btn-success">-->
                                            <!--                Draw Day Winners-->
                                            <!--            </button>-->
                                            <!--        </form>-->
                                            <!--    @elseif($dateWinnerCount > 0)-->
                                            <!--        <button type="button" class="btn btn-sm btn-secondary" disabled>-->
                                            <!--            Winners Selected-->
                                            <!--        </button>-->
                                            <!--    @elseif($date === $todayDate)-->
                                            <!--        <button type="button" class="btn btn-sm btn-secondary" disabled>-->
                                            <!--            Today Not Allowed-->
                                            <!--        </button>-->
                                            <!--    @else-->
                                            <!--        <button type="button" class="btn btn-sm btn-secondary" disabled>-->
                                            <!--            Only Yesterday Allowed-->
                                            <!--        </button>-->
                                            <!--    @endif-->
                                            <!--</div>-->
                                        </div>
                                    </td>
                                </tr>

                                <!--@if($date === $yesterdayDate)-->
                                <!--    <tr>-->
                                <!--        <td colspan="8" style="background:#fff8e1;">-->
                                <!--            <div style="width:100%; max-height:180px; overflow-y:auto;">-->
                                <!--            <form action="{{ route('post.quiz.answer.choose.winner', 0) }}"-->
                                <!--                  method="POST"-->
                                <!--                  class="d-flex justify-content-center align-items-center"-->
                                <!--                  style="gap:8px; flex-wrap:wrap;"-->
                                <!--                  onsubmit="return submitManualWinnerFromIndex(this);">-->
                                <!--                @csrf-->
                                
                                <!--                <strong>{{ __('Manual Winner') }}:</strong>-->
                                
                                <!--                <select name="answer_id" class="form-control form-control-sm mt-3" style="width:280px;" required>-->
                                <!--                    <option value="">Select participant</option>-->
                                <!--                    @foreach($dateCandidates as $candidate)-->
                                <!--                        <option value="{{ $candidate->answer_id }}">-->
                                <!--                            {{ $candidate->name ?? 'N/A' }}-->
                                <!--                            @if($candidate->phone)-->
                                <!--                                - {{ $candidate->phone }}-->
                                <!--                            @endif-->
                                <!--                            (Right: {{ $candidate->correct_count }})-->
                                <!--                        </option>-->
                                <!--                    @endforeach-->
                                <!--                </select>-->
                                
                                <!--                <select name="position" class="form-control form-control-sm mt-3" style="width:100px;" required>-->
                                <!--                    <option value="">Rank</option>-->
                                <!--                    <option value="1">1st</option>-->
                                <!--                    <option value="2">2nd</option>-->
                                <!--                    <option value="3">3rd</option>-->
                                <!--                </select>-->
                                
                                <!--                <button type="submit" class="btn btn-sm btn-success" {{ $dateCandidates->count() == 0 ? 'disabled' : '' }}>-->
                                <!--                    Select Winner-->
                                <!--                </button>-->
                                
                                <!--                @if($dateCandidates->count() == 0)-->
                                <!--                    <span class="text-muted">{{ __('No correct participants found.') }}</span>-->
                                <!--                @endif-->
                                <!--            </form>-->
                                <!--             </div>-->
                                <!--        </td>-->
                                <!--    </tr>-->
                                <!--@else-->
                                <!--    <tr>-->
                                <!--        <td colspan="8" style="background:#f8f9fa;">-->
                                <!--            <span class="text-muted">-->
                                <!--                Manual winner selection is allowed only for yesterday.-->
                                <!--            </span>-->
                                <!--        </td>-->
                                <!--    </tr>-->
                                <!--@endif-->

                                @if($dateWinners->count() > 0)
                                    <tr>
                                        <td colspan="8" style="background:#f8f9fa;">
                                            <div class="row text-center" style="max-height: 250px; overflow-y: auto;">
                                                @foreach($dateWinners as $winner)
                                                    @php
                                                        $winnerCandidate = $dateCandidates->firstWhere('user_id', $winner->answer->user_id ?? null);
                                                        $winnerRightCount = $winnerCandidate->correct_count ?? 0;
                                                    @endphp
                                                
                                                    <div class="col-md-4 mb-2">
                                                        <div style="border:1px solid #ddd;border-radius:6px;padding:10px;background:#fff;">
                                                            <strong>
                                                                {{ $winner->position }}{{ $winner->position == 1 ? 'st' : ($winner->position == 2 ? 'nd' : 'rd') }} Winner
                                                            </strong>
                                                
                                                            <br>
                                                
                                                            {{ $winner->answer->name ?? $winner->answer->user->name ?? 'N/A' }}
                                                
                                                            @if(!empty($winner->answer->phone))
                                                                <br>
                                                                <small>{{ $winner->answer->phone }}</small>
                                                            @endif
                                                
                                                            @if(!empty($winner->answer->email))
                                                                <br>
                                                                <small>{{ $winner->answer->email }}</small>
                                                            @endif
                                                
                                                            <br>
                                                            <span class="badge badge-info">
                                                                Right Answers: {{ $winnerRightCount }}
                                                            </span>
                                                
                                                            <br>
                                                            <span class="badge badge-success">
                                                                {{ $prizes[$winner->position] ?? '' }}
                                                            </span>
                                                
                                                            <form action="{{ route('post.quiz.winner.remove', $winner->id) }}"
                                                                  method="POST"
                                                                  style="display:inline-block; margin-top:6px;"
                                                                  onsubmit="return confirm('Remove this winner?');">
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
                                        </td>
                                    </tr>
                                @endif
                                

                                @foreach($dateQuizzes as $quiz)
                                    @php
                                        $postTitle = $quiz->post->title ?? 'N/A';
                                        $shortTitle = \Illuminate\Support\Str::limit($postTitle, 45);
                                        $correctAnswerText = $quiz->{'option_'.$quiz->correct_answer} ?? 'N/A';
                                        $collapseId = 'quizDetails'.$quiz->id;
                                    @endphp

                                    <tr>
                                        <td title="{{ $postTitle }}">
                                            {{ $shortTitle }}
                                        </td>

                                        <td>
                                            <button type="button"
                                                    class="btn btn-sm btn-primary"
                                                    data-toggle="collapse"
                                                    data-target="#{{ $collapseId }}"
                                                    aria-expanded="false"
                                                    aria-controls="{{ $collapseId }}">
                                                {{ __('Show Quiz') }}
                                            </button>

                                            <div class="collapse mt-2" id="{{ $collapseId }}">
                                                <div style="padding:10px; border:1px solid #ddd; border-radius:4px; background:#fafafa;">
                                                    <strong>{{ $quiz->question }}</strong>

                                                    <ol style="margin-top:8px; padding-left:18px; text-align:left; display:inline-block;">
                                                        <li>{{ $quiz->option_1 }}</li>
                                                        <li>{{ $quiz->option_2 }}</li>
                                                        <li>{{ $quiz->option_3 }}</li>
                                                        <li>{{ $quiz->option_4 }}</li>
                                                    </ol>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <span class="badge badge-success">
                                                {{ \Illuminate\Support\Str::limit($correctAnswerText, 35) }}
                                            </span>
                                        </td>

                                        <td>{{ $quiz->total_participants }}</td>
                                        <td>{{ $quiz->right_count }}</td>
                                        <td>{{ $quiz->wrong_count }}</td>
                                        <td>{{ $quiz->winners_count }}</td>

                                        <td>
                                            <a href="{{ route('post.quiz.participants', $quiz->id) }}"
                                               class="btn btn-info btn-sm">
                                                {{ __('Participants') }}
                                            </a>

                                            <form action="{{ route('post.quiz.destroy', $quiz->id) }}"
                                                  method="POST"
                                                  style="display:inline-block;"
                                                  onsubmit="return confirm('Are you sure you want to delete this quiz? All participants and winners will also be deleted.');">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">{{ __('No quiz found.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $quizzes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function submitManualWinnerFromIndex(form) {
    var answerId = form.querySelector('[name="answer_id"]').value;

    if (!answerId) {
        alert('Please select a participant.');
        return false;
    }

    form.action = "{{ url('admin/post-quiz-answer') }}/" + answerId + "/choose-winner";

    return confirm('Select this participant as winner? Existing winner for this position will be replaced.');
}
</script>
@endsection
