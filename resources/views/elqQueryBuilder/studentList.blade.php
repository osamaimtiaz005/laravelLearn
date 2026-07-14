<h2>Student List from Eloquent Query Builder</h2>

{{-- Flash messages after insert / update / delete --}}
@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif
@if(session('error'))
    <p style="color: red;">{{ session('error') }}</p>
@endif

{{--
  SEARCH form → GET (no @csrf needed)
  Route has NO {keyword} in the path, so route('...') needs no extra arg.
  The input name="keyword" becomes ?keyword=... in the URL automatically.
--}}
<form action="{{ route('elqQueryBuilder.searchStudent') }}" method="get">
    <input type="text" name="keyword" placeholder="Search by name, email, or batch" value="{{ request('keyword') }}" required>
    <button type="submit">Search</button>
</form>
{{--
  ADD form → POST (matches Route::post('/addStudent'...))
  Links (<a href>) always send GET — that only works for show/filter routes.
--}}

<form action="{{ route('elqQueryBuilder.addStudent') }}" method="post">
    @csrf
    <input type="text" name="name" placeholder="Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="batch" placeholder="Batch" required>
    <button type="submit">Add Student</button>
</form>

<br>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Batch</th>
        <th>Update (POST form)</th>
        <th>Delete (POST form)</th>
    </tr>
    @forelse($data as $student)
    <tr>
        {{-- These are GET links — OK, because those routes are Route::get --}}
        <td><a href="{{ route('elqQueryBuilder.studentById', $student->id) }}">{{ $student->id }}</a></td>
        <td><a href="{{ route('elqQueryBuilder.studentByName', $student->name) }}">{{ $student->name }}</a></td>
        <td><a href="{{ route('elqQueryBuilder.studentByEmail', $student->email) }}">{{ $student->email }}</a></td>
        <td><a href="{{ route('elqQueryBuilder.studentByBatch', $student->batch) }}">{{ $student->batch }}</a></td>

        {{--
          UPDATE must be POST.
          <a href="..."> would send GET → MethodNotAllowedHttpException.
          method="post" + @csrf matches Route::post('/updateStudent/{id}'...)
        --}}
        <td>
            <form action="{{ route('elqQueryBuilder.updateStudent', $student->id) }}" method="post">
                @csrf
                <input type="text" name="name" value="{{ $student->name }}" required>
                <input type="email" name="email" value="{{ $student->email }}" required>
                <input type="text" name="batch" value="{{ $student->batch }}" required>
                <button type="submit">Update</button>
            </form>
        </td>

        {{-- DELETE must be POST (same reason as update) --}}
        <td>
            <form
                action="{{ route('elqQueryBuilder.deleteStudent', $student->id) }}"
                method="post"
                onsubmit="return confirm('Delete student #{{ $student->id }}?');"
            >
                @csrf
                <button type="submit">Delete</button>
            </form>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6">No students found</td>
    </tr>
    @endforelse
</table>
