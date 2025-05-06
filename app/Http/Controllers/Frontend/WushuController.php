<?php

namespace App\Http\Controllers\Frontend;

use Inertia\Inertia;
use App\Models\Course;
use App\Models\Service;
use App\Models\Category;
use App\Models\User; // 添加 User 模型
use Illuminate\Support\Facades\Auth; // 引入 Auth
use Illuminate\Support\Facades\Hash; // 引入 Hash
use Illuminate\Http\Request;
use App\Models\ContactRecord;
use App\Http\Controllers\Controller;

class WushuController extends Controller
{
    // 首頁 - 獲取課程資料
    public function home()
    {
        // 取得所有課程類別與其關聯課程
        $categories = Category::with('courses')->get();
        $featuredCourses = Course::where('is_featured', true)->take(2)->get(); // 主打課程
        
        // 將課程按類別分組
        $coursesByCategory = [];
        foreach ($categories as $category) {
            $coursesByCategory[$category->name] = $category->courses->map(function ($course) {
                $lessons = $course->chapters()->count(); // 取得章節數量

                return [
                    'id' => $course->id,
                    'name' => $course->name,
                    'price' => $course->price,
                    'introduction' => $course->introduction,
                    'lessons' => $lessons,
                    'duration' => '2小時/堂', // 假設固定時長
                ];
            });
        }

        return Inertia::render('frontend/Home', [
            'categories' => $categories,
            'coursesByCategory' => $coursesByCategory,
            'featuredCourses' => $featuredCourses,
        ]);
    }

    // 查 服務與課程頁 課程、服務
    public function list()
    {
        $categories = Category::with('courses')->get();
        $services = Service::all();

        return Inertia::render('frontend/ServiceCourse', [
            'categories' => $categories,
            'services' => $services,
        ]);
    }

    // 查 課程介紹頁 課程關聯章節
    public function intro($id)
    {
        $course = Course::with(['chapters'])->find($id);
        if ($course == null) return redirect('/wushu/ServiceCourse');

        return Inertia::render('frontend/CourseIntro', [
            'course' => $course,
        ]);
    }

    // 聯絡我們頁 ContactUs
    function contact()
    {
        return Inertia::render('frontend/ContactUs');
    }

    // 新增 聯絡我們資料 -> ContactRecord
    function store(Request $request)
    {
        $item = $request->all();
        // 資料
        ContactRecord::create([
            "name" => $item['name'],
            "phone" => $item['phone'],
            "email" => $item['email'],
            "title" => $item['title'],
            "content" => $item['content'],
        ]);
        return redirect(route('wushu.contact'));
    }

    // 新增 登入註冊
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // 登入成功，跳轉會員中心
            return redirect('/wushu/MemberCenter');
        }

        return back()->withErrors([
            'email' => '帳號或密碼錯誤',
        ]);
    }

    // 註冊
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        // 註冊成功，跳轉會員中心
        return redirect('/wushu/MemberCenter');
    }
}
