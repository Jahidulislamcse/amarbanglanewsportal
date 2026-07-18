@extends('layouts.admin')
<style>
    .course-card {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        background: #fff;
    }

    .course-header {
        background: #ffd8a8;
        border-bottom: 1px solid #ffb07c;
        padding: 15px;
        border-top-left-radius: 9px;
        border-top-right-radius: 9px;
    }

    .module-box {
        background: #ffffff;
        border: 1px solid #f1f1f1;
        border-radius: 8px;
        padding: 12px;
    }

    .section-title {
        font-size: 14px;
        font-weight: 600;
        color: #495057;
    }

    .btn-soft-primary {
        background: #e7f1ff;
        color: #0d6efd;
        border: 1px solid #cfe2ff;
    }

    .btn-soft-danger {
        background: #ffe7e7;
        color: #dc3545;
        border: 1px solid #f5c2c7;
    }

    .btn-soft-success {
        background: #e7f8ee;
        color: #198754;
        border: 1px solid #c3e6cb;
    }

    .table thead {
        background: #f8f9fa;
    }
    
    .questions-wrapper {
        display: none;
        max-height: 400px;
        overflow-y: auto;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 10px;
        background: #f8f9fa;
        margin-top: 10px;
    }
    
    /* Tree view structure */
    .tree-view {
        position: relative;
        padding-left: 10px;
    }
    .tree-item {
        position: relative;
        padding-left: 35px;
        margin-bottom: 20px;
    }
    /* Vertical line connecting items */
    .tree-item::before {
        content: '';
        position: absolute;
        left: 12px;
        top: 0;
        bottom: -20px;
        width: 2px;
        background: #cfe2ff;
    }
    /* Stop the vertical line at the last item */
    .tree-item:last-child::before {
        height: 20px;
        bottom: auto;
    }
    /* Horizontal branch connector line */
    .tree-item::after {
        content: '';
        position: absolute;
        left: 12px;
        top: 20px;
        width: 20px;
        height: 2px;
        background: #cfe2ff;
    }
    /* Circular node icon */
    .tree-item .tree-icon {
        position: absolute;
        left: -2px;
        top: 5px;
        background: #fff;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #0d6efd;
        color: #0d6efd;
        border-radius: 50%;
        font-size: 13px;
        z-index: 2;
        box-shadow: 0 2px 5px rgba(13, 110, 253, 0.15);
    }
    .tree-content {
        background: #ffffff;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .toggle-questions {
        cursor: pointer;
        font-size: 13px;
        color: #0d6efd;
        font-weight: 600;
        margin-top: 8px;
        display: inline-block;
    }

    iframe {
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    input, select {
        border-radius: 6px !important;
    }
</style>
@section('content')
<div class="content-area">

    <h4 class="heading mb-3">Courses Management</h4>

    @include('includes.admin.flash-message')

    {{-- ADD COURSE --}}
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addCourseModal">
        Add Course
    </button>

    @foreach($courses as $course)

        {{-- COURSE CARD --}}
        <div class="course-card mb-4">
            <div class="course-card mb-4">
                <div class="course-header">
            
                    <form action="{{ route('admin.courses.update', $course->id) }}" 
                          method="POST" 
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
            
                        <div class="row align-items-center g-3">
            
                            <div class="col-12 col-sm-3 col-md-1 text-center">

                                <div class="position-relative d-inline-block">
                            
                                    {{-- Preview Image --}}
                                    <img id="preview{{ $course->id }}"
                                         src="{{ $course->cover_img ? asset('assets/images/courses/'.$course->cover_img) : 'https://via.placeholder.com/120x80?text=No+Image' }}"
                                         style="height:80px;width:120px;object-fit:cover;border-radius:8px;border:1px solid #ddd;">
                            
                                    {{-- Hidden File Input --}}
                                    <input type="file"
                                           name="cover_img"
                                           id="coverInput{{ $course->id }}"
                                           hidden
                                           onchange="previewImage(event, 'preview{{ $course->id }}')">
                            
                                    {{-- Edit Icon --}}
                                    <label for="coverInput{{ $course->id }}"
                                           style="
                                                position:absolute;
                                                bottom:6px;
                                                right:6px;
                                                background:#0d6efd;
                                                color:#fff;
                                                width:26px;
                                                height:26px;
                                                border-radius:50%;
                                                display:flex;
                                                align-items:center;
                                                justify-content:center;
                                                cursor:pointer;
                                                font-size:13px;
                                                box-shadow:0 2px 6px rgba(0,0,0,0.25);
                                           ">
                                        <i class="fas fa-pen"></i>
                                    </label>
                            
                                </div>
                            
                            </div>
            
                            {{-- TITLE --}}
                            <div class="col-12 col-sm-9 col-md-3">
                                <label class="form-label">Course Title</label>
                                <input type="text" name="title" value="{{ $course->title }}" class="form-control">
                            </div>
            
                            {{-- PRICE --}}
                            <div class="col-12 col-sm-6 col-md-2">
                                <label class="form-label">Price</label>
                                <input type="number" name="price" value="{{ $course->price }}" class="form-control">
                            </div>
            
                            {{-- STATUS --}}
                            <div class="col-12 col-sm-6 col-md-2">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="1" {{ $course->status ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !$course->status ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
            
                            {{-- ACTIONS --}}
                            <div class="col-12 col-md-4 text-md-end text-start mb-3">
                                <label class="form-label d-block">&nbsp;</label>
                                <button type="submit" class="btn btn-success me-1">Update</button>
                                
                                <button type="button" 
                                        class="btn btn-primary me-1" 
                                        onclick="toggleQuestions('addModuleForm{{ $course->id }}')">
                                    <i class="fas fa-plus"></i> Add Module
                                </button>
            
                                <button type="button" form="deleteForm{{ $course->id }}" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this course?')">Delete</button>
                            </div>
            
                        </div>
                    </form>
            
                    <form id="deleteForm{{ $course->id }}" action="{{ route('admin.courses.destroy', $course->id) }}" 
                          method="POST" 
                          style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
            
                </div>
            </div>

            <div class="card-body">


                {{-- ADD MODULE --}}
                <div id="addModuleForm{{ $course->id }}" style="display: none; background: #f8f9fa; border: 1px solid #e2e8f0; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                    <form action="{{ route('admin.modules.store') }}" method="POST" class="mb-0">
                        @csrf
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
 
                        <div class="row mt-2 g-2">
                            <div class="col-12 col-md-5"><input name="title" class="form-control" placeholder="Module title" required></div>
                            <div class="col-12 col-md-4"><input name="youtube_link" class="form-control" placeholder="YouTube link" required></div>
                            <div class="col-6 col-md-1"><input name="order" class="form-control" type="number" placeholder="Order" required></div>
                            <div class="col-6 col-md-2"><button class="btn btn-success w-100">Add Module</button></div>
                        </div>
                    </form>
                </div>

                {{-- MODULES --}}
                <div class="section-title mb-2">Modules</div>
                <div style="max-height: 500px; overflow-y: auto; padding-right: 8px; border: 1px solid #f1f1f1; border-radius: 8px; background: #fafafa; padding: 10px; margin-top: 10px;">
                    <div class="tree-view mt-2">
                    @foreach($course->modules->sortBy('order') as $module)

                       <div class="tree-item">
                           <div class="tree-icon">
                               <i class="fas fa-play-circle"></i>
                           </div>
                           <div class="tree-content">

                        <div class="mb-2">

                            <form action="{{ route('admin.modules.update', $module->id) }}"
                                  method="POST"
                                  class="row g-2 align-items-center mb-2">
                                @csrf
                                @method('PUT')
                            
                                {{-- Title --}}
                                <div class="col-12 col-md-3">
                                    <input type="text"
                                           name="title"
                                           value="{{ $module->title }}"
                                           class="form-control form-control-sm"
                                           placeholder="Module Title">
                                </div>
                            
                                {{-- YouTube Link --}}
                                <div class="col-12 col-md-4">
                                    <input type="text"
                                           name="youtube_link"
                                           value="{{ $module->youtube_link }}"
                                           class="form-control form-control-sm"
                                           placeholder="YouTube Link">
                                </div>
                            
                                {{-- Order --}}
                                <div class="col-6 col-md-1">
                                    <input type="number"
                                           name="order"
                                           value="{{ $module->order }}"
                                           class="form-control form-control-sm"
                                           placeholder="Order">
                                </div>
                            
                                {{-- Actions --}}
                                <div class="col-12 col-md-4 d-flex gap-2 align-items-center justify-content-md-end justify-content-start flex-wrap">
                                    <button class="btn btn-primary btn-sm px-3" style="height: 31px; display: inline-flex; align-items: center; justify-content: center;">
                                       Update
                                    </button>
                                
                                    @if($module->exam)
                                        <button type="button" 
                                                form="deleteExamForm{{ $module->exam->id }}" 
                                                class="btn btn-warning btn-sm px-2 text-dark font-weight-bold" 
                                                style="height: 31px; display: inline-flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600;" 
                                                onclick="return confirm('Are you sure you want to delete this exam?')">
                                            Delete Exam
                                        </button>
                                    @endif

                                    <button formaction="{{ route('admin.modules.delete', $module->id) }}"
                                            formmethod="POST"
                                            name="_method"
                                            value="DELETE"
                                            class="btn btn-danger btn-sm px-3"
                                            style="height: 31px; display: inline-flex; align-items: center; justify-content: center;"
                                            onclick="return confirm('Delete this module?')">
                                        @csrf
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </form>
                            
                            @if($module->exam)
                                <form id="deleteExamForm{{ $module->exam->id }}" action="{{ route('admin.exams.delete', $module->exam->id) }}" method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        
                        </div>

                        {{-- VIDEO --}}
                        <iframe width="100%" height="180"
                            src="{{ str_replace('watch?v=','embed/',$module->youtube_link) }}"
                            style="max-width:320px; aspect-ratio:16/9; height:auto; border-radius:8px; border:1px solid #e9ecef;"
                            allowfullscreen></iframe>

                        {{-- EXAM --}}
                        <div class="mt-3">

                            @if($module->exam)



                                {{-- ADD QUESTION --}}
                                <div id="addQuestionForm{{ $module->id }}" style="display: none; background: #f8f9fa; border: 1px solid #e2e8f0; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                                    <form action="{{ route('admin.questions.store', $module->exam->id) }}" method="POST">
                                        @csrf

                                        <input name="question" class="form-control mb-1" placeholder="Question" required>

                                        <div class="row">
                                            <div class="col"><input name="option_a" class="form-control mb-1" placeholder="A"></div>
                                            <div class="col"><input name="option_b" class="form-control mb-1" placeholder="B"></div>
                                        </div>

                                        <div class="row">
                                            <div class="col"><input name="option_c" class="form-control mb-1" placeholder="C"></div>
                                            <div class="col"><input name="option_d" class="form-control mb-1" placeholder="D"></div>
                                        </div>

                                        <div class="row">
                                            <div class="col">
                                                <select name="correct_answer" class="form-control mb-1">
                                                    <option value="">Correct Answer</option>
                                                    <option>A</option>
                                                    <option>B</option>
                                                    <option>C</option>
                                                    <option>D</option>
                                                </select>
                                            </div>
                                            <div class="col">
                                                <input type="number" name="mark" class="form-control mb-1" placeholder="Mark">
                                            </div>
                                        </div>

                                        <button class="btn btn-primary btn-sm mt-1">Save Question</button>
                                    </form>
                                </div>

                                <div class="section-title mt-3 mb-2 d-flex justify-content-between align-items-center px-3 py-2"
                                     style="background:#f1f5f9;border:1px solid #e2e8f0;border-radius:8px;">
                                
                                    <span style="font-weight:600;color:#334155;">
                                        Questions
                                    </span>
                                
                                    <div class="d-flex gap-2 align-items-center">
                                        {{-- Toggle Add Question Form --}}
                                        <button type="button" 
                                                class="btn btn-info btn-sm" 
                                                onclick="toggleQuestions('addQuestionForm{{ $module->id }}')"
                                                style="border-radius:20px; font-size:12px; padding: 4px 12px;">
                                            <i class="fas fa-plus mr-1"></i> Add Question
                                        </button>

                                        {{-- Toggle Questions List --}}
                                        <span class="toggle-questions"
                                              onclick="toggleQuestions('q{{ $module->id }}')"
                                              style="
                                                  background:#0d6efd;
                                                  color:#fff;
                                                  padding:4px 12px;
                                                  border-radius:20px;
                                                  font-size:12px;
                                                  cursor:pointer;
                                                  margin-top: 0;
                                                  display: inline-block;
                                              ">
                                            Show / Hide
                                        </span>
                                    </div>
                                </div>
                                
                                <div id="q{{ $module->id }}" class="questions-wrapper">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm mt-3">
                                            <thead>
                                                <tr>
                                                    <th>Question</th>
                                                    <th>A</th>
                                                    <th>B</th>
                                                    <th>C</th>
                                                    <th>D</th>
                                                    <th>Correct</th>
                                                    <th>Mark</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                    
                                            <tbody>
                                                @foreach($module->exam->questions as $q)
                                                    <tr>
                                                        <td colspan="8">
                                                            <form action="{{ route('admin.questions.update', $q->id) }}" method="POST">
                                                                @csrf
                                    
                                                                <input name="question" value="{{ $q->question }}" class="form-control mb-1">
                                    
                                                                <div class="row">
                                                                    <div class="col"><input name="option_a" value="{{ $q->option_a }}" class="form-control"></div>
                                                                    <div class="col"><input name="option_b" value="{{ $q->option_b }}" class="form-control"></div>
                                                                    <div class="col"><input name="option_c" value="{{ $q->option_c }}" class="form-control"></div>
                                                                    <div class="col"><input name="option_d" value="{{ $q->option_d }}" class="form-control"></div>
                                                                </div>
                                    
                                                                <div class="row mt-1">
                                                                    <div class="col">
                                                                        <select name="correct_answer" class="form-control">
                                                                            <option {{ $q->correct_answer=='A'?'selected':'' }}>A</option>
                                                                            <option {{ $q->correct_answer=='B'?'selected':'' }}>B</option>
                                                                            <option {{ $q->correct_answer=='C'?'selected':'' }}>C</option>
                                                                            <option {{ $q->correct_answer=='D'?'selected':'' }}>D</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col">
                                                                        <input type="number" name="mark" value="{{ $q->mark }}" class="form-control">
                                                                    </div>
                                                                </div>
                                    
                                                                <button class="btn btn-primary btn-sm mt-1">Update</button>
                                                            </form>
                                    
                                                            <form action="{{ route('admin.questions.delete', $q->id) }}" method="POST" class="mt-1">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-danger btn-sm">Delete</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            @else
                                <form action="{{ route('admin.exams.store', $module->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-warning btn-sm">Create Exam</button>
                                </form>
                            @endif

                           </div>
                       </div>
                   </div>

                    @endforeach
                    </div>
                </div>

            </div>
        </div>

    @endforeach
</div>

{{-- ADD COURSE MODAL --}}
<div class="modal fade" id="addCourseModal">
    <div class="modal-dialog">
        <form action="{{ route('admin.courses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5>Add Course</h5>
                </div>

                <div class="modal-body">
                    <input name="title" class="form-control mb-2" placeholder="Title">
                    <input type="file" name="cover_img" class="form-control mb-2" onchange="previewImage(event, 'newPreview')">
                    <img id="newPreview" style="height:60px;display:none;margin-bottom:10px;">
                    <input name="price" class="form-control mb-2" placeholder="Price">

                    <select name="status" class="form-control">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Save</button>
                </div>
            </div>

        </form>
    </div>
</div>
<script>
function previewImage(event, id) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById(id);
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}

function toggleQuestions(id) {
    var el = document.getElementById(id);
    if (el.style.display === "none" || el.style.display === "") {
        el.style.display = "block";
    } else {
        el.style.display = "none";
    }
}
</script>
@endsection