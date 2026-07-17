{{--
================================================================================
DASHBOARD — DYNAMIC CONTENT from the controller
================================================================================
Controller passes arrays/objects → Blade prints them with {{ }} and @foreach.

This is the real-world pattern:
  DB / API / session  →  Controller prepares $data  →  View displays it
  Layout stays dumb about business data; page section uses the variables.
================================================================================
--}}

@extends('layout-demo.layouts.app')

@section('title', 'Dashboard')

@section('subtitle', 'Dynamic data passed from LayoutDemoController@dashboard')

@section('content')
    <div class="card">
        <h1>Hello, {{ $user['name'] }}</h1>
        <p class="muted">
            Role: <strong>{{ $user['role'] }}</strong>
            |
            Email: {{ $user['email'] }}
        </p>
    </div>

    {{-- Dynamic stats grid --}}
    <div class="stats" style="margin-bottom: 1rem;">
        @foreach ($stats as $label => $value)
            <div class="stat">
                <strong>{{ $value }}</strong>
                <span class="muted">{{ $label }}</span>
            </div>
        @endforeach
    </div>

    <div class="card">
        <h2>Recent activity</h2>
        @forelse ($activities as $item)
            <p>
                <strong>{{ $item['title'] }}</strong>
                <span class="muted">— {{ $item['time'] }}</span>
            </p>
        @empty
            <p class="muted">No activity yet.</p>
        @endforelse
    </div>

    <div class="card">
        <h2>Flash message demo</h2>
        <p class="muted">Click to store a session flash, then redirect back (real-world after save).</p>
        <a class="btn" href="{{ route('layout-demo.flash') }}">Trigger success flash</a>
    </div>

    <div class="explain">
        <strong>Dynamic content:</strong>
        Variables like <code>$user</code>, <code>$stats</code>, <code>$activities</code>
        were created in the controller and passed with
        <code>return view('...', compact('user', 'stats', 'activities'))</code>.
        The layout did not need to know about them — only this page section uses them.
    </div>
@endsection

@push('scripts')
<script>
    console.log('[layout-demo] dashboard data rendered for:', @json($user['name']));
</script>
@endpush
