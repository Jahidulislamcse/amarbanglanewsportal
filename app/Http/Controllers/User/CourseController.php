<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BookPurchase;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function userIndex()
    {
        $courses = Course::where('status', 1)->get();
        return view('user.courses.index', compact('courses'));
    }
    
    public function show(Course $course)
    {
        $user = Auth::user();
    
        $isPurchased = BookPurchase::where('user_id', $user->id)
            ->where('book_id', $course->id) // reuse logic style
            ->where('status', 'approved')
            ->exists();
    
        $course->load('modules.exam.questions');
    
        return view('courses.show', compact('course', 'isPurchased'));
    }
}