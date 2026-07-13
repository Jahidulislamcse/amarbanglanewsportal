 @extends('layouts.user')
<style>

    .reporter-widget .reporter-chip {
        position: fixed;
        top: 50%;
        right: 0; 
        transform: translateY(-50%);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        z-index: 1500;
    }
    
    .reporter-widget .reporter-chip .btn {
        writing-mode: vertical-rl;
        text-orientation: mixed;
        padding: 10px 15px;
        font-weight: 600;
        border-radius: 0.25rem 0 0 0.25rem;
        background: #E61B2E;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        transition: background-color 0.3s;
        cursor: pointer;
        color: #fff;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .reporter-widget .reporter-chip .btn:hover {
        background-color: #FCB900;
    }
    
    .reporter-widget .reporter-chip .btn .pull-indicator {
        font-size: 18px;
        color: #fff;
        position: absolute;
        left: -15px; 
        top: 50%;
        transform: translateY(-50%) rotate(180deg); 
        transition: transform 0.3s ease;
    }
    
    .reporter-widget .reporter-panel.show ~ .reporter-chip .btn .pull-indicator {
        transform: translateY(-50%) rotate(0deg); 
    }

    .reporter-widget .reporter-chip .chip-images {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 10px;
    }
    
    .reporter-widget .reporter-chip .chip-images img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .reporter-widget .reporter-chip .chip-images img:hover {
        transform: scale(1.1);
        box-shadow: 0 2px 8px rgba(0,0,0,0.4);
    }
 
    .reporter-widget .reporter-panel {
        position: fixed;
        top: 0;
        right: -300px; 
        width: 280px;
        height: 100%;
        background: #E61B2E;
        color: #fff;
        z-index: 1400;
        transition: right 0.3s ease;
        box-shadow: -2px 0 8px rgba(0,0,0,0.2);
        display: flex;
        flex-direction: column;
    }
    
    .reporter-widget .reporter-panel.show {
        right: 0;
    }
    
    .reporter-widget .reporter-panel .header {
        padding: 1rem;
        font-weight: 600;
        border-bottom: 1px solid rgba(255,255,255,0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: black;
    }
    
    .reporter-widget .reporter-panel .body {
        padding: 1rem;
        overflow-y: auto;
        flex: 1;
    }
    
    .reporter-widget .reporter-panel .reporter-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px;
        background-color: rgba(255,255,255,0.1);
        border-radius: 8px;
        margin-bottom: 10px;
        transition: background 0.2s;
    }
    
    .reporter-widget .reporter-panel .reporter-item:hover {
        background-color: rgba(255,255,255,0.2);
    }
    
    .reporter-widget .reporter-panel .reporter-item img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
    }
    
    .reporter-widget .reporter-panel .reporter-info p {
        font-size: 13px;
        font-weight: 500;
        margin: 0;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    
    .reporter-widget .reporter-panel .reporter-item p,
    .reporter-widget .reporter-panel .reporter-info small,
    .reporter-widget .reporter-panel .no-reporters {
        color: #fff; 
    }
    
    .reporter-widget .reporter-panel .reporter-info small {
        font-size: 12px;
    }
    .reporter-item {
        position: relative;
        padding-top: 12px;
    }

    .rank-badge {
        position: absolute;
        top: -8px;
        left: 50%;
        transform: translateX(-50%);
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        color: #fff;
        z-index: 5;
    }

    .reporter-item:nth-child(1) .rank-badge {
        background: gold;
        color: #000;
    }
    .reporter-item:nth-child(2) .rank-badge {
        background: silver;
        color: #000;
    }
    .reporter-item:nth-child(3) .rank-badge {
        background: #cd7f32; 
    }

    .reporter-widget {
        position: relative;
    }

    .reporter-chip {
        transition: right 0.3s ease;
    }

    .reporter-widget.open .reporter-chip {
        right: 280px; 
    }

    .pull-indicator {
        transition: transform 0.3s ease;
    }
    
    .reporter-widget.open .pull-indicator {
        transform: translateY(-50%) rotate(0deg);
    }

    .course-wrapper{
        margin: auto;
    }
    
    .course-title{
        font-size: 22px;
        font-weight: 600;
        color:#2c3e50;
    }
    
    .module-item{
        border-left: 3px solid #0d6efd;
        padding-left: 18px;
        margin-bottom: 35px;
        position: relative;
    }
    
    .module-item::before{
        content:'';
        position:absolute;
        left:-8px;
        top:6px;
        width:14px;
        height:14px;
        background:#0d6efd;
        border-radius:50%;
    }
    
    .module-title{
        font-size:16px;
        font-weight:600;
        color:#34495e;
    }
    
    .video-box iframe{
        width:100%;
        height:360px;
        border-radius:10px;
        border:1px solid #e9ecef;
    }
    
    .quiz-box{
        background:#f8f9fa;
        border:1px solid #e9ecef;
        border-radius:8px;
        padding:15px;
    }
</style>

@section('content')
<div class="content-area">
    <div class="course-wrapper mt-5">
        <div class="course-title mb-3">
            {{ $course->title }}
        </div>

        @foreach($course->modules->sortBy('order') as $module)

            <div class="module-item">

                {{-- Module title --}}
                <div class="module-title mb-2">
                    Module {{ $loop->iteration }} : {{ $module->title }}
                </div>

                @if($module->youtube_link)
                    <div class="video-box mb-3">
                        <iframe
                            src="{{ str_replace('watch?v=', 'embed/', $module->youtube_link) }}"
                            allowfullscreen>
                        </iframe>
                    </div>
                @endif

                @if($module->exam && $module->exam->questions->count())
                    <button class="btn btn-outline-primary btn-sm mb-3"
                            data-toggle="collapse"
                            data-target="#quiz{{ $module->id }}">
                        Attempt Module Quiz
                    </button>
                
                    <div class="collapse" id="quiz{{ $module->id }}">
                        <div class="quiz-box mt-2">
                
                            <div class="row">
                                @foreach($module->exam->questions as $q)
                                    <div class="col-md-6 mb-4">
                                        <div class="p-3 border rounded bg-white h-100">
                
                                            <strong class="d-block mb-2">
                                                {{ $loop->iteration }}. {{ $q->question }}
                                            </strong>
                
                                            <div class="row">
                                                <label class="col-6 mb-2">
                                                    <input type="radio" name="q{{ $q->id }}" disabled>
                                                    {{ $q->option_a }}
                                                </label>
                                            
                                                <label class="col-6 mb-2">
                                                    <input type="radio" name="q{{ $q->id }}" disabled>
                                                    {{ $q->option_b }}
                                                </label>
                                            
                                                <label class="col-6">
                                                    <input type="radio" name="q{{ $q->id }}" disabled>
                                                    {{ $q->option_c }}
                                                </label>
                                            
                                                <label class="col-6">
                                                    <input type="radio" name="q{{ $q->id }}" disabled>
                                                    {{ $q->option_d }}
                                                </label>
                                            </div>
                                                                
                                        </div>
                                    </div>

                                    @if($loop->iteration % 2 == 0)
                                        </div><div class="row">
                                    @endif
                
                                @endforeach
                            </div>
                
                        </div>
                    </div>
                @endif

            </div>

        @endforeach
    </div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-pprn3073KE6tl6vY5Jw6n4+DZv4pAP9HjT2yyL6w7C1gZ5+u+RW4my06MZ20kGmN" 
        crossorigin="anonymous">
</script>

@endsection
