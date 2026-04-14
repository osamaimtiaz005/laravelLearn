{{--
    KEYWORDS ON THIS PAGE

    @extends / @section — same as blade-basics: child slots into learn/layout.blade.php.

    @foreach ($items as $key => $item) … @endforeach
    — Loops every element; $key optional if you only need values.

    @forelse ($items as $item) … @empty … @endforelse
    — Like foreach, but if the collection/array is empty, the @empty branch runs once.

    $loop (inside foreach/forelse)
    — Magic variable: ->first, ->last, ->index, ->iteration, ->remaining, ->depth, ->parent.

    Interview: What is compact() in a controller?
    A: PHP helper: compact('a','b') builds ['a' => $a, 'b' => $b]. Often paired with view().

    Interview: foreach vs forelse?
    A: foreach always iterates; forelse gives a dedicated empty state without manual count checks.

    Interview: @continue / @break?
    A: Same as PHP inside loops: skip iteration vs exit loop.
--}}
@extends('learn.layout')

@section('title', 'Loops & data — learning')

@section('content')
    <h1>Loops and passing data</h1>
    {{--
        Data here comes from routes/web.php: view('learn.blade-loops-data', [ 'tags' => ..., 'orders' => ... ]).
        — view(): Laravel helper; first arg is dot-path under resources/views.
        — Second arg: array keys become variable names in this template ($tags, $orders).
    --}}
    <p>Route passed <code>$tags</code> (array) and <code>$orders</code> (empty array) to show @@foreach vs @@forelse.</p>

    <div class="card">
        <h2>@@foreach</h2>
        {{-- @foreach: repeats inner markup for each element; @endforeach closes the loop. --}}
        <ul>
            @foreach ($tags as $index => $tag)
                <li>{{ $index + 1 }}. {{ $tag }}</li>
            @endforeach
        </ul>
    </div>

    <div class="card">
        <h2>@@forelse (empty case)</h2>
        {{--
            @forelse: if $orders has items, loop body runs per item.
            @empty: runs when iterable is empty (no manual @if(count()===0)).
            @endforelse: closes the whole construct.
        --}}
        @forelse ($orders as $order)
            <p>Order #{{ $order['id'] }}</p>
        @empty
            <p><em>No orders — this is the <code>@@empty</code> branch (good interview talking point).</em></p>
        @endforelse
    </div>

    <div class="card">
        <h2>$loop inside @@foreach</h2>
        {{-- Interview: What is $loop? A: Blade injects a stdClass with metadata about the current iteration. --}}
        <ul>
            @foreach ($tags as $tag)
                <li>
                    {{ $tag }}
                    @if ($loop->first)
                        <small>(first item: <code>$loop->first</code>)</small>
                    @endif
                    @if ($loop->last)
                        <small>(last item: <code>$loop->last</code>)</small>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@endsection
