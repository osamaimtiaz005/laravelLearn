<?php

// ============================================================
// FILE: CollectionMethodsController.php
// PURPOSE: Show & RUN Laravel Collection methods (beginner demo)
// ============================================================

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Collection = a smart list of items (like an array with helpers).
 *
 * Eloquent get() returns a Collection, so you can:
 *   map, filter, sort, pluck, groupBy, sum, each, contains, first, ...
 *
 * Visit: GET /collection-methods
 */
class CollectionMethodsController extends Controller
{
    /**
     * GET /collection-methods
     * HTML page: explanation + live results for each method
     */
    public function index()
    {
        // Sample data (always works even if DB is empty)
        $numbers = collect([5, 2, 8, 2, 10, 3]);
        $people = collect([
            ['name' => 'Ali', 'city' => 'Lahore', 'score' => 80],
            ['name' => 'Sara', 'city' => 'Karachi', 'score' => 95],
            ['name' => 'Omar', 'city' => 'Lahore', 'score' => 70],
            ['name' => 'Noor', 'city' => 'Islamabad', 'score' => 95],
        ]);

        // ---------- map() = transform EACH item into something new ----------
        // Word by word: take each number, multiply by 2, return new collection
        $mapResult = $numbers->map(fn ($n) => $n * 2);

        // ---------- filter() = KEEP only items that pass the test ----------
        $filterResult = $numbers->filter(fn ($n) => $n > 4);

        // ---------- sort() = order values (ascending); values() resets keys ----------
        $sortResult = $numbers->sort()->values();

        // ---------- pluck() = pull ONE field from each item ----------
        $pluckResult = $people->pluck('name');

        // ---------- groupBy() = group items under a key ----------
        $groupByResult = $people->groupBy('city');

        // ---------- sum() = add numbers together ----------
        $sumResult = $people->sum('score');

        // ---------- each() = loop; does NOT return a new list for chaining output ----------
        $eachLog = [];
        $people->each(function ($person) use (&$eachLog) {
            // &$eachLog = allow writing into outer variable
            $eachLog[] = $person['name'].' scored '.$person['score'];
        });

        // ---------- contains() = does the collection include this value? ----------
        $containsYes = $numbers->contains(8);   // true
        $containsNo = $numbers->contains(99);  // false
        $containsCallback = $people->contains(fn ($p) => $p['score'] >= 95);

        // ---------- first() = first item (or first that matches) ----------
        $firstItem = $people->first();
        $firstHigh = $people->first(fn ($p) => $p['score'] >= 90);

        // Optional: same ideas on real User models if any exist
        $users = User::query()->limit(5)->get();
        $userNames = $users->pluck('name');
        $userEmails = $users->map(fn ($u) => strtolower((string) $u->email));

        return view('collection_methods.index', [
            'numbers' => $numbers,
            'people' => $people,
            'mapResult' => $mapResult,
            'filterResult' => $filterResult,
            'sortResult' => $sortResult,
            'pluckResult' => $pluckResult,
            'groupByResult' => $groupByResult,
            'sumResult' => $sumResult,
            'eachLog' => $eachLog,
            'containsYes' => $containsYes,
            'containsNo' => $containsNo,
            'containsCallback' => $containsCallback,
            'firstItem' => $firstItem,
            'firstHigh' => $firstHigh,
            'userNames' => $userNames,
            'userEmails' => $userEmails,
        ]);
    }

    /**
     * GET /collection-methods/json
     * Same demos as JSON (easy to inspect in browser)
     */
    public function json()
    {
        $numbers = collect([5, 2, 8, 2, 10, 3]);

        return response()->json([
            'source' => $numbers->values(),
            'map_x2' => $numbers->map(fn ($n) => $n * 2)->values(),
            'filter_gt_4' => $numbers->filter(fn ($n) => $n > 4)->values(),
            'sort' => $numbers->sort()->values(),
            'pluck_demo' => collect([
                ['name' => 'Ali'],
                ['name' => 'Sara'],
            ])->pluck('name'),
            'sum' => $numbers->sum(),
            'contains_8' => $numbers->contains(8),
            'first' => $numbers->first(),
            'groupBy_parity' => $numbers->groupBy(fn ($n) => $n % 2 === 0 ? 'even' : 'odd'),
        ]);
    }
}
