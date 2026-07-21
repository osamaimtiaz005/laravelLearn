{{--
================================================================================
ACCESSORS & MUTATORS DEMO VIEW
File: resources/views/accessor_mutator/index.blade.php
URL:  GET /accessors   (AccessorController@list)
Form: POST /save        (MutatorController@save)

HOW THIS PAGE TEACHES BOTH IDEAS
--------------------------------
1) FORM (bottom of request cycle starts here)
   You type mixed-case values → mutator may change them BEFORE insert.

2) LIST (below the form)
   You read $student->name / email → accessors change what you SEE.

Try this experiment:
  Type name:  aLi
  Type email: Ali@Mail.COM
  Type batch: 2024
  Submit

Then compare:
  - Database (phpMyAdmin students table):
      name  ≈ "ALi"          ← mutator: ucfirst()
      email ≈ "Ali@Mail.COM" ← no email mutator (stored as typed)
      batch ≈ "2024"         ← no mutator

  - This page list:
      Name:  ALI             ← accessor: strtoupper()
      Email: ali@mail.com    ← accessor: strtolower()
      Batch: 2024            ← no accessor

Interview one-liner:
  Mutator = format on write. Accessor = format on read.
================================================================================
--}}

<h2>Accessors and Mutators</h2>

{{-- Flash messages from MutatorController redirect --}}
@if (session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif
@if (session('error'))
    <p style="color: red;">{{ session('error') }}</p>
@endif

<div style="border: 1px solid #ccc; padding: 1rem; margin: 1rem 0;">
    <h3>1) Form → triggers MUTATOR on save</h3>
    <p>
        <code>POST /save</code> → <code>MutatorController@save</code><br>
        Assigning <code>$student-&gt;name = ...</code> calls <code>setNameAttribute()</code> on the model.
    </p>

    {{--
      method="post" is required for MutatorController (Route::post).
      @csrf inserts a hidden token; without it Laravel returns 419 Page Expired.
      route('accessor_mutator.save') is safer than hardcoding "/save".
    --}}
    <form action="{{ route('accessor_mutator.save') }}" method="post">
        @csrf
        <input type="text" name="name" placeholder="Name (try: aLi)" required>
        <input type="email" name="email" placeholder="Email (try: Ali@Mail.COM)" required>
        <input type="text" name="batch" placeholder="Batch (e.g. 2024)" required>
        <button type="submit">Submit (save with mutator)</button>
    </form>
</div>

<div style="border: 1px solid #ccc; padding: 1rem; margin: 1rem 0;">
    <h3>2) List → triggers ACCESSORS on read</h3>
    <p>
        Data loaded by <code>AccessorController@list</code> using <code>Student::all()</code>.<br>
        In Blade, <code>@{{ $student-&gt;name }}</code> calls <code>getNameAttribute()</code>
        and <code>@{{ $student-&gt;email }}</code> calls <code>getEmailAttribute()</code>.
    </p>
    <p><strong>Expected display:</strong> name UPPERCASE, email lowercase, batch unchanged.</p>

    @forelse ($students as $student)
        {{--
          Each {{ $student->... }} is an attribute read.
          That is the moment the accessor runs (not during Student::all() itself).
        --}}
        <p><strong>ID:</strong> {{ $student->id }}</p>
        <p><strong>Name (accessor → UPPER):</strong> {{ $student->name }}</p>
        <p><strong>Email (accessor → lower):</strong> {{ $student->email }}</p>
        <p><strong>Batch (no accessor):</strong> {{ $student->batch }}</p>

        {{--
          Optional learning: show raw DB value vs accessor value for name.
          getRawOriginal('name') bypasses getNameAttribute().
        --}}
        <p style="color: #666; font-size: 0.9rem;">
            Raw DB name (no accessor): {{ $student->getRawOriginal('name') }}
        </p>
        <hr>
    @empty
        <p>No students yet. Submit the form above to create one.</p>
    @endforelse
</div>

<div style="background: #f5f5f5; padding: 1rem; margin: 1rem 0;">
    <h3>Quick reference</h3>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>Concept</th>
            <th>When it runs</th>
            <th>Method pattern</th>
            <th>This project</th>
        </tr>
        <tr>
            <td>Mutator</td>
            <td>On write / save</td>
            <td><code>setNameAttribute($value)</code></td>
            <td><code>ucfirst($value)</code> → stored in DB</td>
        </tr>
        <tr>
            <td>Accessor</td>
            <td>On read / display</td>
            <td><code>getNameAttribute($value)</code></td>
            <td><code>strtoupper($value)</code> → shown in view</td>
        </tr>
        <tr>
            <td>Accessor</td>
            <td>On read / display</td>
            <td><code>getEmailAttribute($value)</code></td>
            <td><code>strtolower($value)</code> → shown in view</td>
        </tr>
    </table>
</div>
