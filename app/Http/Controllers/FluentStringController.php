<?php

namespace App\Http\Controllers;

/**
 * FluentStringController — demo of Laravel Fluent Strings (Stringable).
 *
 * Route: GET /fluent-string → index()
 *
 * Two ways to start a fluent string:
 *   1) Str::of('Hello World')
 *   2) str('Hello World')          // helper (same idea)
 *
 * Both return Illuminate\Support\Stringable — an object you can chain methods on.
 * When you need a plain PHP string again: ->toString() or cast (string) $s
 */

use Illuminate\Support\Str;

class FluentStringController extends Controller
{
    public function index()
    {
        $raw = '  Laravel Fluent Strings Are Awesome!  ';

        /*
        | PROBLEM (plain PHP): nested functions are hard to read (inside-out).
        |   strtolower(str_replace(' ', '-', trim($raw)))
        |
        | SOLUTION (fluent): left → right, like a pipeline.
        |   Str::of($raw)->trim()->lower()->replace(' ', '-')
        */
        $slugFromTitle = Str::of($raw)
            ->trim()
            ->lower()
            ->replace('!', '')
            ->replace(' ', '-')
            ->toString();

        // Same result with the str() helper
        $withHelper = str($raw)->trim()->slug()->toString();

        $examples = [
            // --- Case / style ---
            'upper' => Str::of('laravel')->upper()->toString(),
            'lower' => Str::of('LARAVEL')->lower()->toString(),
            'title' => Str::of('hello laravel world')->title()->toString(),
            'headline' => Str::of('hello laravel world')->headline()->toString(),
            'studly' => Str::of('hello_laravel_world')->studly()->toString(),
            'camel' => Str::of('hello_laravel_world')->camel()->toString(),
            'snake' => Str::of('HelloLaravelWorld')->snake()->toString(),
            'kebab' => Str::of('HelloLaravelWorld')->kebab()->toString(),
            'slug' => Str::of('Hello Laravel World!')->slug()->toString(),

            // --- Trim / append / prepend ---
            'trim' => Str::of('  spaces  ')->trim()->toString(),
            'append' => Str::of('Hello')->append(' World')->toString(),
            'prepend' => Str::of('World')->prepend('Hello ')->toString(),
            'finish' => Str::of('/users')->finish('/')->toString(),   // ensure ends with /
            'start' => Str::of('users')->start('/')->toString(),      // ensure starts with /

            // --- Replace / remove ---
            'replace' => Str::of('Hello World')->replace('World', 'Laravel')->toString(),
            'replaceFirst' => Str::of('foo foo')->replaceFirst('foo', 'bar')->toString(),
            'replaceLast' => Str::of('foo foo')->replaceLast('foo', 'bar')->toString(),
            'remove' => Str::of('Hello World')->remove('World')->trim()->toString(),

            // --- Extract parts ---
            'before' => Str::of('name@example.com')->before('@')->toString(),
            'after' => Str::of('name@example.com')->after('@')->toString(),
            'beforeLast' => Str::of('a.b.c.txt')->beforeLast('.')->toString(),
            'afterLast' => Str::of('a.b.c.txt')->afterLast('.')->toString(),
            'between' => Str::of('[Laravel]')->between('[', ']')->toString(),

            // --- Length / limit ---
            'length' => Str::of('Laravel')->length(),
            'limit' => Str::of('This is a long sentence')->limit(10)->toString(),
            'words' => Str::of('This is a long sentence')->words(3)->toString(),
            // excerpt() returns a plain string (not Stringable) — no ->toString()
            'excerpt' => Str::of('The quick brown fox jumps')->excerpt('brown', [
                'radius' => 5,
            ]),

            // --- Checks (return bool — not usually chained further for display) ---
            'contains' => Str::of('Laravel Framework')->contains('Framework'),
            'startsWith' => Str::of('https://example.com')->startsWith('https'),
            'endsWith' => Str::of('photo.jpg')->endsWith(['.jpg', '.png']),
            'isEmpty' => Str::of('')->isEmpty(),
            'isNotEmpty' => Str::of('hi')->isNotEmpty(),
            'isJson' => Str::of('{"ok":true}')->isJson(),
            'isUrl' => Str::of('https://laravel.com')->isUrl(),

            // --- Mask / pad / wrap ---
            'mask' => Str::of('1234567890')->mask('*', 3, 4)->toString(),
            'padLeft' => Str::of('7')->padLeft(3, '0')->toString(),
            'wrap' => Str::of('Laravel')->wrap('"')->toString(),

            // --- when() — conditional chain (problem: avoid if/else clutter) ---
            // when() returns a Stringable object (not a plain string) — no ->toString()
            // so we need to call toString() to get a plain string
            'when_true' => Str::of('laravel')
                ->when(true, fn ($s) => $s->upper())
                ->toString(),
            'when_false' => Str::of('laravel')
                ->when(false, fn ($s) => $s->upper(), fn ($s) => $s->title())
                ->toString(),
        ];

        return view('fluent_string.index', [
            'raw' => $raw,
            'slugFromTitle' => $slugFromTitle,
            'withHelper' => $withHelper,
            'examples' => $examples,
        ]);
    }
}
