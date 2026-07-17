{{--
|--------------------------------------------------------------------------
| PAGINATION BLADE VIEW — DETAILED GUIDE
|--------------------------------------------------------------------------
|
| Data comes from PaginationController:
|   $students = Student::paginate(2);
|
| $students is a LengthAwarePaginator, NOT a plain array.
| You can still loop it with @foreach / @forelse like a collection.
|
| URL examples:
|   /paginate/list         → page 1
|   /paginate/list?page=2  → page 2
|
--}}

<h1>Paginate List of Students</h1>

{{--
|--------------------------------------------------------------------------
| PAGINATION META INFO (optional — useful for learning / UI)
|--------------------------------------------------------------------------
|
| These methods exist because paginate() is length-aware.
| They will NOT all work the same with simplePaginate() / cursorPaginate().
|
--}}
<p>
    Showing {{ $students->firstItem() }} – {{ $students->lastItem() }}
    of {{ $students->total() }} students
    |
    Page {{ $students->currentPage() }} of {{ $students->lastPage() }}
    |
    Per page: {{ $students->perPage() }}
</p>

{{--
| Example: only show meta when there is data
|
| @if ($students->total() > 0)
|     <p>Total: {{ $students->total() }}</p>
| @endif
--}}

<table border="1">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Batch</th>
        </tr>
    </thead>
    <tbody>
        {{--
        |--------------------------------------------------------------------------
        | LOOPING PAGINATED RESULTS
        |--------------------------------------------------------------------------
        |
        | @forelse = @foreach + empty fallback
        | Each $student is an Eloquent Student model for THIS page only
        | (e.g. only 2 rows when paginate(2) is used).
        |
        | Same loop with @foreach:
        |
        |   @foreach ($students as $student)
        |       <tr>...</tr>
        |   @endforeach
        |
        |   @if ($students->isEmpty())
        |       <tr><td colspan="3">No students found</td></tr>
        |   @endif
        --}}
        @forelse ($students as $student)
            <tr>
                <td>{{ $student->name }}</td>
                <td>{{ $student->email }}</td>
                <td>{{ $student->batch }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3">No students found</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{--
|--------------------------------------------------------------------------
| PAGINATION LINKS — $students->links()  ✅ (ACTIVE)
|--------------------------------------------------------------------------
|
| links() renders Previous / page numbers / Next HTML.
|
| Default Bootstrap-style views ship with Laravel.
| Clicking a link adds ?page=N to the current URL.
|
| Important:
|   Put links() OUTSIDE the table (usually below the list).
|   Only show links when there is more than one page:
|
|   @if ($students->hasPages())
|       {{ $students->links() }}
|   @endif
|
--}}
{{--
| Use pagination::default (text « Previous / Next ») instead of the default
| Tailwind view — Tailwind SVGs render as huge arrows without Tailwind CSS.
--}}
<div style="margin-top: 20px; font-size: 18px;">
    {{ $students->links('pagination::default') }}
</div>

{{--
|--------------------------------------------------------------------------
| MORE links() EXAMPLES (commented — copy one into the div above to try)
|--------------------------------------------------------------------------
|
| 1) Keep other query string params (search, filters, sort):
|    {{ $students->withQueryString()->links() }}
|
| 2) Append custom params manually:
|    {{ $students->appends(['sort' => 'name', 'dir' => 'asc'])->links() }}
|
| 3) Add a URL fragment (#section):
|    {{ $students->fragment('students')->links() }}
|    → /paginate/list?page=2#students
|
| 4) Control how many page numbers show beside current page:
|    {{ $students->onEachSide(1)->links() }}
|    Example current page 5 → maybe: 4 5 6  (fewer numbers)
|
| 5) Use a specific pagination view theme (Laravel built-ins):
|    {{ $students->links('pagination::bootstrap-5') }}
|    {{ $students->links('pagination::tailwind') }}
|    {{ $students->links('pagination::simple-bootstrap-5') }}
|
| 6) Manual Previous / Next (without full numbered links):
|
|    @if ($students->onFirstPage())
|        <span>Previous</span>
|    @else
|        <a href="{{ $students->previousPageUrl() }}">Previous</a>
|    @endif
|
|    @if ($students->hasMorePages())
|        <a href="{{ $students->nextPageUrl() }}">Next</a>
|    @else
|        <span>Next</span>
|    @endif
|
| 7) Build your own numbered links:
|
|    @for ($i = 1; $i <= $students->lastPage(); $i++)
|        <a href="{{ $students->url($i) }}"
|           @if ($i === $students->currentPage()) style="font-weight:bold" @endif>
|            {{ $i }}
|        </a>
|    @endfor
|
| 8) JSON / API style (in a controller, not Blade):
|    return Student::paginate(2);
|    // Laravel returns JSON with: data, current_page, last_page, per_page, total, links...
|
--}}
