<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Module;
use App\Models\Exam;
use App\Models\CoursePurchase;
use App\Models\Question;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('modules.exam.questions')->get();
        return view('admin.courses.index', compact('courses'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'status' => 'required|boolean',
            'cover_img' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
    
        $course = new Course();
        $course->title = $request->title;
        $course->price = $request->price;
        $course->status = $request->status;
    
        if ($request->hasFile('cover_img')) {
            $coverName = time() . '_' . $request->file('cover_img')->getClientOriginalName();
            $request->file('cover_img')->move(public_path('assets/images/courses/'), $coverName);
            $course->cover_img = $coverName;
        }
    
        $course->save();
    
        return back()->with('success','Course created successfully');
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'status' => 'required|boolean',
            'cover_img' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
    
        $course->title = $request->title;
        $course->price = $request->price;
        $course->status = $request->status;
    
        if ($request->hasFile('cover_img')) {
    
            $oldCover = public_path('assets/images/courses/' . $course->cover_img);
            if ($course->cover_img && file_exists($oldCover)) unlink($oldCover);
    
            $coverName = time() . '_' . $request->file('cover_img')->getClientOriginalName();
            $request->file('cover_img')->move(public_path('assets/images/courses/'), $coverName);
            $course->cover_img = $coverName;
        }
    
        $course->save();
    
        return back()->with('success','Course updated successfully');
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return back()->with('success','Course deleted successfully');
    }


    public function storeModule(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'title' => 'required|string|max:255',
            'youtube_link' => 'required|string',
            'order' => 'required|integer',
        ]);

        Module::create($request->only('course_id','title','youtube_link','order'));

        return back()->with('success','Module added successfully');
    }

    public function updateModule(Request $request, Module $module)
    {
        $request->validate([
            'title' => 'required',
            'youtube_link' => 'required',
            'order' => 'required|integer',
        ]);
    
        $module->update([
            'title' => $request->title,
            'youtube_link' => $request->youtube_link,
            'order' => $request->order,
        ]);
    
        return back()->with('success', 'Module updated');
    }

    public function deleteModule(Module $module)
    {
        $module->delete();
        return back()->with('success','Module deleted successfully');
    }
    
    public function deleteExam(Exam $exam)
    {
        $exam->delete();
        return back()->with('success', 'Exam deleted successfully');
    }


    public function storeExam($moduleId)
    {
        $module = Module::findOrFail($moduleId);

        if (!$module->exam) {
            Exam::create(['module_id' => $moduleId]);
        }

        return back()->with('success','Exam created for this module');
    }


    public function storeQuestion(Request $request, $examId)
    {
        $request->validate([
            'question' => 'required',
            'option_a' => 'required',
            'option_b' => 'required',
            'option_c' => 'required',
            'option_d' => 'required',
            'correct_answer' => 'required|in:A,B,C,D',
            'mark' => 'required|integer',
        ]);

        Question::create([
            'exam_id' => $examId,
            'question' => $request->question,
            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->option_c,
            'option_d' => $request->option_d,
            'correct_answer' => $request->correct_answer,
            'mark' => $request->mark,
        ]);

        return back()->with('success','Question added successfully');
    }

    public function updateQuestion(Request $request, Question $question)
    {
        $request->validate([
            'question' => 'required',
            'option_a' => 'required',
            'option_b' => 'required',
            'option_c' => 'required',
            'option_d' => 'required',
            'correct_answer' => 'required|in:A,B,C,D',
            'mark' => 'required|integer',
        ]);

        $question->update([
            'question' => $request->question,
            'option_a' => $request->option_a,
            'option_b' => $request->option_b,
            'option_c' => $request->option_c,
            'option_d' => $request->option_d,
            'correct_answer' => $request->correct_answer,
            'mark' => $request->mark,
        ]);

        return back()->with('success','Question updated successfully');
    }

    public function deleteQuestion(Question $question)
    {
        $question->delete();
        return back()->with('success','Question deleted successfully');
    }
    public function purchaseCourse(Request $request, $courseId)
    {
        $request->validate([
            'phone_number' => 'required',
             'operator' => 'required|in:bkash,nagad,rocket',
        ]);
    
       CoursePurchase::create([
            'user_id' => auth()->id(),
            'course_id' => $courseId,
            'phone_number' => $request->phone_number,
            'operator' => $request->operator,
            'status' => 'pending',
        ]);
    
        return back()->with('success', 'Course purchase request sent.');
    }
    
    public function show(Course $course)
    {
        $purchased = CoursePurchase::where('user_id', auth()->id())
                        ->where('course_id', $course->id)
                        ->where('status', 'approved')
                        ->exists();
    
        if (!$purchased) {
            return redirect()->route('user.dashboard')
                ->with('error', 'You must purchase this course first.');
        }
    
        return view('user.courses.show', compact('course'));
    }
    
    public function approveCoursePurchase($id)
    {
        $purchase = CoursePurchase::findOrFail($id);
        $purchase->status = 'approved';
        $purchase->save();
    
        return back()->with('success','Approved');
    }
    
    public function rejectCoursePurchase($id)
    {
        $purchase = CoursePurchase::findOrFail($id);
        $purchase->status = 'rejected';
        $purchase->save();
    
        return back()->with('success','Rejected');
    }
}