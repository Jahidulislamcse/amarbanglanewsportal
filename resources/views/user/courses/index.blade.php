@extends('layouts.app')

@section('content')

<div class="container mt-4">

    <h4>Courses</h4>

    <div class="row">

        @foreach($courses as $course)
            <div class="col-md-4 mb-3">
                <div class="card">

                    <div class="card-body">
                        <h5>{{ $course->title }}</h5>

                        <p>Price: {{ $course->price }}</p>

                        <a href="{{ route('courses.show', $course->id) }}" class="btn btn-primary btn-sm">
                            View Course
                        </a>

                    </div>

                </div>
            </div>
        @endforeach

    </div>

</div>

@endsection