@extends('layouts.admin')

@section('content')

@php
$groupTeams = [
    'Group A' => ['Mexico','South Korea','Czech Republic','South Africa'],
    'Group B' => ['Bosnia','Canada','Qatar','Switzerland'],
    'Group C' => ['Brazil','Haiti','Morocco','Scotland'],
    'Group D' => ['Australia','Paraguay','Turkey','USA'],
    'Group E' => ['Ivory Coast','Curacao','Ecuador','Germany'],
    'Group F' => ['Japan','Netherlands','Sweden','Tunisia'],
    'Group G' => ['Belgium','Egypt','Iran','New Zealand'],
    'Group H' => ['Cape Verde','Saudi Arabia','Spain','Uruguay'],
    'Group I' => ['France','Iraq','Norway','Senegal'],
    'Group J' => ['Algeria','Argentina','Austria','Jordan'],
    'Group K' => ['Colombia','DR Congo','Portugal','Uzbekistan'],
    'Group L' => ['Croatia','England','Ghana','Panama'],
];
@endphp

<div class="content-area">

    <div class="mr-breadcrumb">
        <h4 class="heading">World Cup 2026 Admin Panel</h4>

    </div>

    @include('includes.admin.form-success')
    @include('includes.admin.flash-message')


    <div class="row">
        <!-- Add New Standing Card -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Add New Standing</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.worldcup.store') }}" method="POST">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label">Group Name *</label>
                                <select name="group_name" class="form-control" required>
                                    <option value="Round of 32">Round of 32</option>
                                    @foreach(array_keys($groupTeams) as $groupName)
                                        <option value="{{ $groupName }}">{{ $groupName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Team Name *</label>
                                <input type="text" name="team_name" class="form-control" placeholder="e.g. Argentina" required>
                            </div>
                            <div class="col-md-4 mt-2">
                                <label class="form-label">Rank</label>
                                <input type="number" name="rank" class="form-control" value="1">
                            </div>
                            <div class="col-md-4 mt-2">
                                <label class="form-label">Played</label>
                                <input type="number" name="played" class="form-control" value="0">
                            </div>
                            <div class="col-md-4 mt-2">
                                <label class="form-label">Win</label>
                                <input type="number" name="win" class="form-control" value="0">
                            </div>
                            <div class="col-md-4 mt-2">
                                <label class="form-label">Draw</label>
                                <input type="number" name="draw" class="form-control" value="0">
                            </div>
                            <div class="col-md-4 mt-2">
                                <label class="form-label">Lose</label>
                                <input type="number" name="lose" class="form-control" value="0">
                            </div>
                            <div class="col-md-4 mt-2">
                                <label class="form-label">Goal Diff (GD)</label>
                                <input type="number" name="goal_diff" class="form-control" value="0">
                            </div>
                            <div class="col-md-12 mt-2">
                                <label class="form-label">Points</label>
                                <input type="number" name="points" class="form-control" value="0">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success mt-3 w-100">Add Standing</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add New Match Card -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Add New Match</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.worldcup_match.store') }}" method="POST">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-12">
                                <label class="form-label">Group / Stage Name *</label>
                                <select name="group_name" class="form-control" required>
                                    <option value="Round of 32">Round of 32</option>
                                    @foreach(array_keys($groupTeams) as $groupName)
                                        <option value="{{ $groupName }}">{{ $groupName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mt-2">
                                <label class="form-label">Home Team *</label>
                                <input type="text" name="home_team" class="form-control" placeholder="e.g. Argentina" required>
                            </div>
                            <div class="col-md-6 mt-2">
                                <label class="form-label">Away Team *</label>
                                <input type="text" name="away_team" class="form-control" placeholder="e.g. Brazil" required>
                            </div>
                            <div class="col-md-6 mt-2">
                                <label class="form-label">Home Score</label>
                                <input type="number" name="home_score" class="form-control" value="0">
                            </div>
                            <div class="col-md-6 mt-2">
                                <label class="form-label">Away Score</label>
                                <input type="number" name="away_score" class="form-control" value="0">
                            </div>
                            <div class="col-md-6 mt-2">
                                <label class="form-label">Match Date *</label>
                                <input type="datetime-local" name="match_date" class="form-control" required>
                            </div>
                            <div class="col-md-6 mt-2">
                                <label class="form-label">Status *</label>
                                <select name="status" class="form-control" required>
                                    <option value="scheduled">Scheduled</option>
                                    <option value="finished">Finished</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success mt-3 w-100">Add Match</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Round of 32 Card -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">🏆 FIFA Round of 32 (রাউন্ড অফ ৩২)</h5>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" id="round32Tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="standings-tab" data-bs-toggle="tab" data-bs-target="#round32-standings" type="button" role="tab" aria-controls="round32-standings" aria-selected="true">Standings (Points Table)</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="matches-tab" data-bs-toggle="tab" data-bs-target="#round32-matches" type="button" role="tab" aria-controls="round32-matches" aria-selected="false">Matches (Schedule)</button>
                </li>
            </ul>
            <div class="tab-content" id="round32TabsContent">
                <!-- Round of 32 Standings Tab -->
                <div class="tab-pane fade show active" id="round32-standings" role="tabpanel" aria-labelledby="standings-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Rank</th>
                                    <th>Team</th>
                                    <th>Played</th>
                                    <th>Win</th>
                                    <th>Draw</th>
                                    <th>Lose</th>
                                    <th>GD</th>
                                    <th>Points</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $r32Standings = $standings->where('group_name', 'Round of 32');
                                @endphp
                                @forelse($r32Standings as $row)
                                    <tr>
                                        <td>{{ $row->rank }}</td>
                                        <td><b>{{ $row->team_name }}</b></td>
                                        <td>{{ $row->played }}</td>
                                        <td>{{ $row->win }}</td>
                                        <td>{{ $row->draw }}</td>
                                        <td>{{ $row->lose }}</td>
                                        <td>{{ $row->goal_diff }}</td>
                                        <td><b>{{ $row->points }}</b></td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $row->id }}">Edit</button>
                                            <form action="{{ route('admin.worldcup.destroy', $row->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Are you sure you want to delete this standing?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">No standings added for Round of 32 yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Round of 32 Matches Tab -->
                <div class="tab-pane fade" id="round32-matches" role="tabpanel" aria-labelledby="matches-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Match Date & Time</th>
                                    <th>Home Team</th>
                                    <th>Score</th>
                                    <th>Away Team</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $r32Matches = $matches->where('group_name', 'Round of 32');
                                @endphp
                                @forelse($r32Matches as $match)
                                    <tr>
                                        <td>{{ $match->match_date }}</td>
                                        <td><b>{{ $match->home_team }}</b></td>
                                        <td><span class="badge bg-secondary p-2 fs-6">{{ $match->home_score }} - {{ $match->away_score }}</span></td>
                                        <td><b>{{ $match->away_team }}</b></td>
                                        <td>
                                            @if($match->status === 'finished')
                                                <span class="badge bg-success">Finished</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Scheduled</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editMatchModal{{ $match->id }}">Edit</button>
                                            <form action="{{ route('admin.worldcup_match.destroy', $match->id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Are you sure you want to delete this match?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Edit Match Modal -->
                                    <div class="modal fade" id="editMatchModal{{ $match->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('admin.worldcup_match.update', $match->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content text-dark">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Match</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row g-2">
                                                            <div class="col-md-12">
                                                                <label class="form-label">Group / Stage Name</label>
                                                                <input type="text" name="group_name" class="form-control" value="{{ $match->group_name }}" required>
                                                            </div>
                                                            <div class="col-md-6 mt-2">
                                                                <label class="form-label">Home Team</label>
                                                                <input type="text" name="home_team" class="form-control" value="{{ $match->home_team }}" required>
                                                            </div>
                                                            <div class="col-md-6 mt-2">
                                                                <label class="form-label">Away Team</label>
                                                                <input type="text" name="away_team" class="form-control" value="{{ $match->away_team }}" required>
                                                            </div>
                                                            <div class="col-md-6 mt-2">
                                                                <label class="form-label">Home Score</label>
                                                                <input type="number" name="home_score" class="form-control" value="{{ $match->home_score }}">
                                                            </div>
                                                            <div class="col-md-6 mt-2">
                                                                <label class="form-label">Away Score</label>
                                                                <input type="number" name="away_score" class="form-control" value="{{ $match->away_score }}">
                                                            </div>
                                                            <div class="col-md-6 mt-2">
                                                                <label class="form-label">Match Date</label>
                                                                <input type="datetime-local" name="match_date" class="form-control" value="{{ date('Y-m-d\TH:i', strtotime($match->match_date)) }}" required>
                                                            </div>
                                                            <div class="col-md-6 mt-2">
                                                                <label class="form-label">Status</label>
                                                                <select name="status" class="form-control" required>
                                                                    <option value="scheduled" @if($match->status === 'scheduled') selected @endif>Scheduled</option>
                                                                    <option value="finished" @if($match->status === 'finished') selected @endif>Finished</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-success">Update Match</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No matches added for Round of 32 yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Standings (Group Wise)</h5>
        </div>
    
        <div class="card-body">
    
            @foreach($groupTeams as $group => $teamsList)
    
                <div class="mb-4 mt-4">
    
                    <h4 class="bg-dark text-white p-2 rounded">
                        {{ $group }}
                    </h4>
    
                    <div class="table-responsive">
    
                        <table class="table table-bordered table-sm">
    
                            <thead class="table-light">
                                <tr>
                                    <th>Rank</th>
                                    <th>Team</th>
                                    <th>Played</th>
                                    <th>Win</th>
                                    <th>Draw</th>
                                    <th>Lose</th>
                                    <th>GF</th>
                                    <th>GA</th>
                                    <th>GD</th>
                                    <th>Points</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
    
                            <tbody>
    
                                @php
                                    $groupData = $standings->where('group_name', $group);
                                @endphp
    
                                @forelse($groupData as $row)
    
                                    <tr>
    
                                        <td>{{ $row->rank }}</td>
                                        <td><b>{{ $row->team_name }}</b></td>
                                        <td>{{ $row->played }}</td>
                                        <td>{{ $row->win }}</td>
                                        <td>{{ $row->draw }}</td>
                                        <td>{{ $row->lose }}</td>
                                        <td>{{ $row->goals_for }}</td>
                                        <td>{{ $row->goals_against }}</td>
                                        <td>{{ $row->goal_diff }}</td>
                                        <td><b>{{ $row->points }}</b></td>
    
                                        <td>
    
                                            <button class="btn btn-primary btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal{{ $row->id }}">
                                                Edit
                                            </button>
    
                                            <!--<form action="{{ route('admin.worldcup.destroy',$row->id) }}"-->
                                            <!--    method="POST"-->
                                            <!--    style="display:inline-block">-->
                                            <!--    @csrf-->
                                            <!--    @method('DELETE')-->
    
                                            <!--    <button class="btn btn-danger btn-sm">-->
                                            <!--        Delete-->
                                            <!--    </button>-->
                                            <!--</form>-->
    
                                        </td>
    
                                    </tr>
                                    
                                    <div class="modal fade" id="editModal{{ $row->id }}">
                                <div class="modal-dialog modal-lg">
    
                                    <form method="POST"
                                        action="{{ route('admin.worldcup.update',$row->id) }}">
    
                                        @csrf
                                        @method('PUT')
    
                                        <div class="modal-content">
    
                                            <div class="modal-header">
                                                <h5>Edit Standing</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
    
                                            <div class="modal-body">
    
                                                <div class="row">
    
                                                    <div class="col-md-6">
                                                        <label>Group</label>
                                                    
                                                        <input type="text"
                                                               class="form-control"
                                                               value="{{ $row->group_name }}"
                                                               readonly>
                                                    
                                                        <input type="hidden"
                                                               name="group_name"
                                                               value="{{ $row->group_name }}">
                                                    </div>
    
                                                   <div class="col-md-6">
                                                        <label>Team</label>
                                                        <input type="text"
                                                               class="form-control"
                                                               value="{{ $row->team_name }}"
                                                               readonly>
                                                    
                                                        <input type="hidden"
                                                               name="team_name"
                                                               value="{{ $row->team_name }}">
                                                    </div>
    
                                                    <div class="col-md-4 mt-2">
                                                        <label>Rank</label>
                                                        <input type="number" name="rank" class="form-control" value="{{ $row->rank }}">
                                                    </div>
    
                                                    <div class="col-md-4 mt-2">
                                                        <label>Played</label>
                                                        <input type="number" name="played" class="form-control" value="{{ $row->played }}">
                                                    </div>
    
                                                    <div class="col-md-4 mt-2">
                                                        <label>Win</label>
                                                        <input type="number" name="win" class="form-control" value="{{ $row->win }}">
                                                    </div>
    
                                                    <div class="col-md-4 mt-2">
                                                        <label>Draw</label>
                                                        <input type="number" name="draw" class="form-control" value="{{ $row->draw }}">
                                                    </div>
    
                                                    <div class="col-md-4 mt-2">
                                                        <label>Lose</label>
                                                        <input type="number" name="lose" class="form-control" value="{{ $row->lose }}">
                                                    </div>
    
                                                    <!--<div class="col-md-4 mt-2">-->
                                                    <!--    <label>Goal Achieved (GF)</label>-->
                                                    <!--    <input type="number" name="goals_for" class="form-control" value="{{ $row->goals_for }}">-->
                                                    <!--</div>-->
    
                                                    <!--<div class="col-md-4 mt-2">-->
                                                    <!--    <label>Goal Against (GA)</label>-->
                                                    <!--    <input type="number" name="goals_against" class="form-control" value="{{ $row->goals_against }}">-->
                                                    <!--</div>-->
    
                                                    <div class="col-md-4 mt-2">
                                                        <label>Goal Difference (GD)</label>
                                                        <input type="number" name="goal_diff" class="form-control" value="{{ $row->goal_diff }}">
                                                    </div>
    
                                                    <div class="col-md-4 mt-2">
                                                        <label>Points</label>
                                                        <input type="number" name="points" class="form-control" value="{{ $row->points }}">
                                                    </div>
    
                                                </div>
    
                                            </div>
    
                                            <div class="modal-footer">
                                                <button class="btn btn-success">Update</button>
                                            </div>
    
                                        </div>
    
                                    </form>
    
                                </div>
                            </div>
    
                                @empty
    
                                    <tr>
                                        <td colspan="11" class="text-center text-muted">
                                            No teams in this group
                                        </td>
                                    </tr>
    
                                @endforelse
    
                            </tbody>
    
                        </table>
    
                    </div>
    
                </div>
    
            @endforeach
    
        </div>
    </div>

</div>



@endsection

@section('scripts')
<script>
    const teams = @json($groupTeams);

    document.addEventListener('DOMContentLoaded', function () {

        document.querySelectorAll('.group-select').forEach(groupSelect => {

            const modal = groupSelect.closest('.modal');
            const teamSelect = modal.querySelector('.team-select');

            function loadTeams() {

                const group = groupSelect.value;
                const selectedTeam = teamSelect.dataset.selected?.trim();

                teamSelect.innerHTML = '<option value="">Select Team</option>';

                if (teams[group]) {

                    teams[group].forEach(team => {

                        const option = document.createElement('option');

                        option.value = team;
                        option.textContent = team;

                        if (team === selectedTeam) {
                            option.selected = true;
                        }

                        teamSelect.appendChild(option);
                    });

                }
            }

            loadTeams();

            groupSelect.addEventListener('change', function () {

                teamSelect.dataset.selected = '';

                loadTeams();

            });

            modal.addEventListener('shown.bs.modal', function () {
                loadTeams();
            });

        });

    });
</script>
@endsection