<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * LayoutDemoController
 * --------------------
 * Teaches real-world Blade layouts with shared CSS/JS + dynamic page data.
 *
 * Views live under: resources/views/layout-demo/
 * Assets live under: public/css/layout-demo/ and public/js/layout-demo/
 *
 * URLs:
 *   /layout-demo              → home
 *   /layout-demo/about        → about
 *   /layout-demo/dashboard    → dynamic content
 *   /layout-demo/flash        → session flash demo
 */
class LayoutDemoController extends Controller
{
    /**
     * Static-ish marketing/home page — still uses the shared layout.
     */
    public function home()
    {
        /*
         * No extra data needed here.
         * The child view still gets nav/footer/css/js from the layout.
         *
         * Equivalent examples:
         *   return view('layout-demo.pages.home');
         *   return view('layout-demo.pages.home', ['title' => 'Home']);
         */
        return view('layout-demo.pages.home');
    }

    /**
     * Another child page — proves many pages share one layout.
     */
    public function about()
    {
        return view('layout-demo.pages.about');
    }

    /**
     * DYNAMIC CONTENT example (real-world dashboard pattern).
     *
     * In production, $user / $stats / $activities would come from:
     *   - Auth::user()
     *   - Eloquent queries (Order::count(), etc.)
     *   - APIs / caches
     *
     * Here we use plain arrays so you can see the data flow clearly.
     */
    public function dashboard()
    {
        // Fake logged-in user (replace with auth()->user() in real apps)
        $user = [
            'name'  => 'Ali Khan',
            'email' => 'ali@example.com',
            'role'  => 'Admin',
        ];

        // Fake KPIs for the stats cards
        $stats = [
            'Students'  => 128,
            'Courses'   => 14,
            'Messages'  => 7,
            'Tasks due' => 3,
        ];

        // Fake activity feed
        $activities = [
            ['title' => 'New student registered', 'time' => '2 minutes ago'],
            ['title' => 'Course "Laravel Basics" updated', 'time' => '1 hour ago'],
            ['title' => 'Backup completed', 'time' => 'Yesterday'],
        ];

        /*
         * compact('user', 'stats', 'activities') creates:
         *   ['user' => $user, 'stats' => $stats, 'activities' => $activities]
         *
         * In the Blade file you then use: {{ $user['name'] }}, @foreach ($stats as ...)
         */
        return view('layout-demo.pages.dashboard', compact('user', 'stats', 'activities'));
    }

    /**
     * Real-world flash pattern:
     *   1) Do some action (save form, delete row...)
     *   2) redirect()->with('success', '...')
     *   3) Layout shows session('success') once, then it disappears
     */
    public function flash(Request $request)
    {
        return redirect()
            ->route('layout-demo.dashboard')
            ->with('success', 'Flash message works! This came from the session after redirect.');
    }
}
