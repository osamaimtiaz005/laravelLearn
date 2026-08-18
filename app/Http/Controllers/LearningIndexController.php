<?php

namespace App\Http\Controllers;

/**
 * =============================================================================
 * LEARNING INDEX — main home for this project
 * =============================================================================
 *
 * WHAT: One HTML page that lists every beginner topic practiced here,
 *       with a short explanation, sample code, and links to the demos.
 *
 * WHY:  Routes, controllers, and README are spread across many files.
 *       A beginner needs one starting URL to click through the path.
 *
 * HOW Laravel shows this page:
 *   GET /        → LearningIndexController@index  → view('learning.index')
 *   GET /learn   → same method (alias)
 *
 * This controller does NOT query the database. It only returns a Blade file.
 * Old welcome page is still at GET /welcome (welcome.blade.php unchanged).
 *
 * View: resources/views/learning/index.blade.php
 * =============================================================================
 */
class LearningIndexController extends Controller
{
    /**
     * GET /  and  GET /learn
     *
     * return view('learning.index')
     *   'learning.index' = resources/views/learning/index.blade.php
     *   Dot = folder. Laravel does not run PHP until the view is compiled.
     */
    public function index()
    {
        return view('learning.index');
    }
}
