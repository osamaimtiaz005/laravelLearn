<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class PaginationController extends Controller
{
    /**
     * Show a paginated list of students.
     *
     * Visit: /paginate/list
     * Next page: /paginate/list?page=2
     * Custom page size (if you use example below): /paginate/list?per_page=5
     */
    public function list(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | WHAT IS PAGINATION?
        |--------------------------------------------------------------------------
        |
        | Pagination = splitting a LARGE result set into smaller "pages".
        | Example: 100 students, 10 per page → 10 pages.
        |
        | Without pagination:
        |   Student::all();          // loads EVERY row into memory (slow / heavy)
        |
        | With pagination:
        |   Student::paginate(10);   // loads only 10 rows for the current page
        |
        | Laravel automatically reads ?page= from the URL:
        |   /paginate/list          → page 1
        |   /paginate/list?page=2   → page 2
        |   /paginate/list?page=3   → page 3
        |
        */

        /*
        |--------------------------------------------------------------------------
        | 1) paginate() — FULL / LENGTH-AWARE PAGINATION  ✅ (ACTIVE CODE)
        |--------------------------------------------------------------------------
        |
        | Syntax:
        |   Model::paginate($perPage);
        |   Model::paginate($perPage, $columns, $pageName, $page);
        |
        | Returns: LengthAwarePaginator
        |   - knows TOTAL records
        |   - knows TOTAL pages
        |   - can build numbered links: 1 2 3 4 5
        |
        | SQL idea (simplified):
        |   SELECT count(*) FROM students;          // total
        |   SELECT * FROM students LIMIT 2 OFFSET 0; // page 1
        |   SELECT * FROM students LIMIT 2 OFFSET 2; // page 2
        |   SELECT * FROM students LIMIT 2 OFFSET 4; // page 3
        |
        | OFFSET formula:
        |   offset = (currentPage - 1) * perPage
        |
        | Default per page if you write paginate() with no arg: 15
        |
        */
        $students = Student::paginate(2);

        /*
        |--------------------------------------------------------------------------
        | OTHER EXAMPLES (commented — try one at a time by replacing ACTIVE code)
        |--------------------------------------------------------------------------
        */

        // Example A: 10 records per page
        // $students = Student::paginate(10);

        // Example B: only selected columns
        // $students = Student::paginate(5, ['id', 'name', 'email', 'batch']);

        // Example C: custom page query-string name (?student_page=2 instead of ?page=2)
        // $students = Student::paginate(2, ['*'], 'student_page');
        // Then links become: /paginate/list?student_page=2

        // Example D: force a specific page number from code (ignore URL page)
        // $students = Student::paginate(2, ['*'], 'page', 3); // always show page 3

        // Example E: dynamic per-page from request (?per_page=5)
        // $perPage = (int) $request->input('per_page', 2);
        // $perPage = max(1, min($perPage, 50)); // safety: between 1 and 50
        // $students = Student::paginate($perPage);

        // Example F: paginate AFTER query conditions (WHERE / ORDER BY)
        // $students = Student::where('batch', '2024')
        //     ->orderBy('name')
        //     ->paginate(2);

        // Example G: keep other query params in links (search, filters)
        // Visit: /paginate/list?search=ali&page=2
        // $students = Student::when($request->search, function ($q) use ($request) {
        //         $q->where('name', 'like', '%' . $request->search . '%');
        //     })
        //     ->paginate(2)
        //     ->withQueryString(); // keeps ?search=ali on Next/Prev links
        //
        // Same idea with appends():
        // $students = Student::paginate(2)->appends(['search' => $request->search]);
        // $students = Student::paginate(2)->appends($request->query());

        /*
        |--------------------------------------------------------------------------
        | 2) simplePaginate() — LIGHT / "NEXT-PREV ONLY"
        |--------------------------------------------------------------------------
        |
        | Syntax:
        |   Model::simplePaginate($perPage);
        |
        | Returns: Paginator (NOT length-aware)
        |   - NO total count query (faster on huge tables)
        |   - ONLY "Previous" / "Next" links (no page numbers 1 2 3)
        |   - does NOT know last page number
        |
        | Use when:
        |   - table is very large
        |   - you don't need "Page 5 of 200"
        |
        | Example:
        |   $students = Student::simplePaginate(2);
        |
        */

        /*
        |--------------------------------------------------------------------------
        | 3) cursorPaginate() — CURSOR PAGINATION (BEST FOR HUGE DATA / INFINITE SCROLL)
        |--------------------------------------------------------------------------
        |
        | Syntax:
        |   Model::orderBy('id')->cursorPaginate($perPage);
        |
        | How it works:
        |   Uses a "cursor" (usually last seen id) instead of OFFSET.
        |   URL looks like: ?cursor=eyJpZCI6MTAsIl9wb2ludHNUb05leHRJdGVtcyI6dHJ1ZX0
        |
        | Pros:
        |   - very fast on large datasets (no big OFFSET)
        | Cons:
        |   - cannot jump to page 50 directly
        |   - needs a unique ordered column (often id)
        |
        | Example:
        |   $students = Student::orderBy('id')->cursorPaginate(2);
        |
        */

        /*
        |--------------------------------------------------------------------------
        | 4) paginate() vs simplePaginate() vs cursorPaginate()
        |--------------------------------------------------------------------------
        |
        | Method              | Count query? | Page numbers? | Jump to page N? | Best for
        | ------------------- | ------------ | ------------- | --------------- | --------
        | paginate()          | YES          | YES           | YES             | Normal admin lists
        | simplePaginate()    | NO           | NO (prev/next)| NO              | Faster lists
        | cursorPaginate()    | NO           | NO (prev/next)| NO              | Huge data / APIs
        |
        */

        /*
        |--------------------------------------------------------------------------
        | 5) USEFUL PAGINATOR METHODS / PROPERTIES
        |--------------------------------------------------------------------------
        |
        | After: $students = Student::paginate(2);
        |
        | $students->count();           // items on THIS page
        | $students->total();           // total items in DB (paginate only)
        | $students->perPage();         // e.g. 2
        | $students->currentPage();     // e.g. 1
        | $students->lastPage();        // e.g. 10 (paginate only)
        | $students->hasPages();        // true if more than 1 page
        | $students->hasMorePages();    // true if a next page exists
        | $students->onFirstPage();     // true if page == 1
        | $students->url($page);        // URL for a given page number
        | $students->nextPageUrl();     // URL of next page or null
        | $students->previousPageUrl(); // URL of previous page or null
        | $students->items();           // array of models on this page
        | $students->firstItem();       // index of first item on page (1-based)
        | $students->lastItem();        // index of last item on page
        | $students->links();           // HTML pagination links (for Blade)
        | $students->withQueryString(); // keep other query params in links
        | $students->appends([...]);    // add custom query params to links
        | $students->fragment('list');  // add #list to links
        | $students->onEachSide(1);     // how many page numbers beside current
        |
        | Example dump (for learning):
        |   // dd([
        |   //     'current' => $students->currentPage(),
        |   //     'last'    => $students->lastPage(),
        |   //     'total'   => $students->total(),
        |   //     'perPage' => $students->perPage(),
        |   // ]);
        |
        */

        /*
        |--------------------------------------------------------------------------
        | 6) compact() — PASS DATA TO THE VIEW
        |--------------------------------------------------------------------------
        |
        | compact('students')  ===  ['students' => $students]
        |
        | Same as:
        |   return view('paginate.list', ['students' => $students]);
        |   return view('paginate.list', compact('students'));
        |
        | Multiple variables:
        |   return view('paginate.list', compact('students', 'title'));
        |
        */
        return view('paginate.list', compact('students'));
    }
}
