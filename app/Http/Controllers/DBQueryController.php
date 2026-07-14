<?php

/*
|--------------------------------------------------------------------------
| FILE: DBQueryController.php
|--------------------------------------------------------------------------
|
| WHAT IS THIS FILE?
|   This file is a "Controller".
|   In Laravel, a Controller is a PHP class that sits BETWEEN:
|     1) the Route  (the URL the browser asks for)
|     2) the Database (where your data is stored)
|     3) the View   (the Blade/HTML page the user sees)
|
| SIMPLE FLOW (read this carefully, word by word):
|
|   Browser
|      |
|      |  user types or clicks a URL
|      v
|   routes/web.php   ---- finds which controller method to call
|      |
|      v
|   DBQueryController  ---- talks to the database using Query Builder
|      |
|      v
|   database table "users"  ---- stores rows (id, name, email, phone...)
|      |
|      v
|   Blade view data.blade.php  ---- shows the result as HTML
|
| WHAT IS "QUERY BUILDER"?
|   Query Builder is Laravel's easy way to talk to MySQL/MariaDB
|   WITHOUT writing raw SQL every time.
|
|   Instead of writing:
|       SELECT * FROM users;
|
|   You write:
|       DB::table('users')->get();
|
|   Laravel converts that into real SQL behind the scenes.
|
|--------------------------------------------------------------------------
*/

namespace App\Http\Controllers;

/*
| "namespace" means: this class lives in the App\Http\Controllers folder/group.
| It helps PHP find the class when routes say: DBQueryController::class
*/

use Illuminate\Http\Request;
/*
| Request = the data coming FROM the browser.
| Example: when a form is submitted, name/email/phone arrive inside Request.
| $request->name   means: get the form field named "name".
*/

use Illuminate\Support\Facades\DB;
/*
| DB = Laravel's database "facade" (a shortcut class).
| Facade means: you can call DB::table(...) without creating objects yourself.
| DB::table('users') means: start working with the "users" table.
*/

class DBQueryController extends Controller
{
    /*
    | "extends Controller" means:
    | this class inherits (gets) basic controller features from Laravel's Controller.
    |
    | Each method below is one "action".
    | A route calls ONE method. That method does ONE job.
    */

    /**
     * FLOW FOR getAllData():
     *   1. Route /db-query-builder/all is opened
     *   2. This method runs
     *   3. Query Builder reads ALL rows from "users"
     *   4. Those rows are sent to the Blade view as $data
     *   5. Blade loops with @foreach and draws a table
     */
    public function getAllData()
    {
        // DB::table('users')  -> choose the users table
        // ->get()             -> run SELECT * FROM users and return a collection (list) of rows
        // Each row becomes an object like: $user->id, $user->name, $user->email
        $data = DB::table('users')->get();

        // return view(...) means: load the Blade file and send data to it
        // 'dbQueryBuilder.data' maps to: resources/views/dbQueryBuilder/data.blade.php
        // ['data' => $data] means: inside Blade, the variable will be called $data
        return view('dbQueryBuilder.data', [
            'data' => $data,
            'pageTitle' => 'All Users',
            'info' => 'Showing every row from the users table using DB::table(...)->get()',
        ]);
    }

    /**
     * FLOW FOR getDataById($id):
     *   1. URL looks like /db-query-builder/byid/3
     *   2. Route takes {id} from the URL and passes it as $id
     *   3. Query Builder finds ONLY the row WHERE id = $id
     *   4. View shows that single (or zero) result in the same table
     *
     * WORD BY WORD:
     *   where('id', $id)  = add a condition: column "id" must equal the value in $id
     *   get()             = return matching rows as a list (so @foreach still works)
     */
    public function getDataById($id)
    {
        // We use get() (not first()) so $data is always a list.
        // Blade's @foreach needs a list/collection, not one lonely object.
        $data = DB::table('users')->where('id', $id)->get();

        return view('dbQueryBuilder.data', [
            'data' => $data,
            'pageTitle' => 'User By ID',
            'info' => "Filtered with where('id', $id) — only matching id rows",
        ]);
    }

    /**
     * FLOW FOR getDataByName($name):
     *   URL: /db-query-builder/byname/Ali
     *   Finds rows where the name column equals "Ali"
     */
    public function getDataByName($name)
    {
        $data = DB::table('users')->where('name', $name)->get();

        return view('dbQueryBuilder.data', [
            'data' => $data,
            'pageTitle' => 'User By Name',
            'info' => "Filtered with where('name', '$name')",
        ]);
    }

    /**
     * FLOW FOR getDataByEmail($email):
     *   URL: /db-query-builder/byemail/ali@mail.com
     *   Finds rows where email equals the given email
     */
    public function getDataByEmail($email)
    {
        $data = DB::table('users')->where('email', $email)->get();

        return view('dbQueryBuilder.data', [
            'data' => $data,
            'pageTitle' => 'User By Email',
            'info' => "Filtered with where('email', '$email')",
        ]);
    }

    /**
     * FLOW FOR getDataByPhone($phone):
     *   URL: /db-query-builder/byphone/03001234567
     *   Finds rows where phone equals the given phone
     */
    public function getDataByPhone($phone)
    {
        $data = DB::table('users')->where('phone', $phone)->get();

        return view('dbQueryBuilder.data', [
            'data' => $data,
            'pageTitle' => 'User By Phone',
            'info' => "Filtered with where('phone', '$phone')",
        ]);
    }

    /**
     * FLOW FOR insertData(Request $request):
     *   1. User fills the Insert form and clicks Submit
     *   2. Browser sends a POST request to /db-query-builder/insert
     *   3. Laravel puts form values into $request
     *   4. We build an array of column => value
     *   5. insert() adds ONE new row into the users table
     *   6. redirect() sends the browser back to the all-users page
     *
     * WHY Request $request?
     *   Because form fields (name, email, phone) arrive inside the Request object.
     *   $request->name  = value typed in <input name="name">
     */
    public function insertData(Request $request)
    {
        // Build the data array that will become the new database row.
        // Left side  = column name in the table
        // Right side = value from the form
        $newRow = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            // Many Laravel "users" tables also require a password column.
            // bcrypt() hashes the password so it is not stored as plain text.
            'password' => bcrypt($request->password ?? 'password123'),
            // now() = current date and time for created_at / updated_at
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // insert([...]) runs roughly: INSERT INTO users (...) VALUES (...);
        DB::table('users')->insert($newRow);

        // redirect()->route('name') = go to another named route after saving
        // with('success', ...) stores a one-time flash message for the next page
        return redirect()
            ->route('dbQueryBuilder.all')
            ->with('success', 'New user inserted with Query Builder insert().');
    }

    /**
     * FLOW FOR updateData(Request $request, $id):
     *   1. User edits a row form and clicks Update
     *   2. POST goes to /db-query-builder/update/{id}
     *   3. {id} from the URL becomes $id
     *   4. where('id', $id)->update([...]) changes ONLY that row
     *   5. Redirect back to the list
     *
     * WORD BY WORD:
     *   where('id', $id)  = find the correct row first
     *   update([...])     = change the listed columns on that row
     */
    public function updateData(Request $request, $id)
    {
        $updatedValues = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'updated_at' => now(),
        ];

        // SQL idea: UPDATE users SET name=?, email=?, phone=? WHERE id=?
        DB::table('users')->where('id', $id)->update($updatedValues);

        return redirect()
            ->route('dbQueryBuilder.all')
            ->with('success', "User id {$id} updated with Query Builder update().");
    }

    /**
     * FLOW FOR deleteData($id):
     *   1. User clicks Delete on a row
     *   2. POST goes to /db-query-builder/delete/{id}
     *   3. where('id', $id)->delete() removes that row forever
     *   4. Redirect back to the list
     *
     * IMPORTANT:
     *   Delete is permanent. Always filter with where(...) first,
     *   or you could delete the whole table by mistake.
     */
    public function deleteData($id)
    {
        // SQL idea: DELETE FROM users WHERE id = ?
        DB::table('users')->where('id', $id)->delete();

        return redirect()
            ->route('dbQueryBuilder.all')
            ->with('success', "User id {$id} deleted with Query Builder delete().");
    }

    /**
     * FLOW FOR searchData(Request $request):
     *   1. User types a keyword in the Search form (method GET)
     *   2. Browser opens /db-query-builder/search?keyword=ali
     *   3. $request->keyword reads "ali"
     *   4. where('name', 'like', '%ali%') finds names that CONTAIN "ali"
     *
     * WHAT DOES "like" MEAN?
     *   Exact match:   where('name', 'Ali')        -> only exact "Ali"
     *   Partial match: where('name', 'like', '%Ali%')
     *     % means "anything"
     *     so %Ali% means: anything before Ali + Ali + anything after
     *     Examples that match: Ali, Alias, AmAli, Kali
     */
    public function searchData(Request $request)
    {
        // Read ?keyword=... from the URL query string
        $keyword = $request->keyword;

        $data = DB::table('users')
            ->where('name', 'like', '%' . $keyword . '%')
            // orWhere = OR condition (also search email / phone)
            ->orWhere('email', 'like', '%' . $keyword . '%')
            ->orWhere('phone', 'like', '%' . $keyword . '%')
            ->get();

        return view('dbQueryBuilder.data', [
            'data' => $data,
            'pageTitle' => 'Search Results',
            'info' => "Searched with LIKE '%{$keyword}%' on name, email, and phone",
            'keyword' => $keyword,
        ]);
    }

    /**
     * FLOW FOR sortData(Request $request):
     *   1. User chooses a column (id/name/email/phone) and order (asc/desc)
     *   2. GET /db-query-builder/sort?column=name&order=asc
     *   3. orderBy($column, $order) sorts the rows
     *
     * WORD BY WORD:
     *   asc  = ascending  (A → Z, 1 → 9)
     *   desc = descending (Z → A, 9 → 1)
     */
    public function sortData(Request $request)
    {
        // Whitelist columns so users cannot sort by a fake/unsafe column name
        $allowedColumns = ['id', 'name', 'email', 'phone', 'created_at'];
        $column = in_array($request->column, $allowedColumns) ? $request->column : 'id';

        // Only allow asc or desc
        $order = $request->order === 'desc' ? 'desc' : 'asc';

        // SQL idea: SELECT * FROM users ORDER BY name ASC
        $data = DB::table('users')->orderBy($column, $order)->get();

        return view('dbQueryBuilder.data', [
            'data' => $data,
            'pageTitle' => 'Sorted Users',
            'info' => "Sorted with orderBy('{$column}', '{$order}')",
            'column' => $column,
            'order' => $order,
        ]);
    }
}
/*
|--------------------------------------------------------------------------
| Why does first() Give an Object and Not an Array?
|--------------------------------------------------------------------------
|
| In Laravel's Query Builder and Eloquent, when you execute a query, there
| are multiple methods for fetching results from the database. Let's see why:
|
|   - get()  : Returns a COLLECTION (array-like) of all matching rows.
|              Even if you only get one row, it's wrapped in a collection.
|   - first(): Returns ONLY THE FIRST matching row as a SINGLE OBJECT,
|              or null if there is none.
|
| This is by design—because get() assumes you want lots of results, and
| lets you loop through them (foreach). But first() is a shorthand for:
|   "Just give me the first row, I don't want a list/array/collection."
|
| Example:
|   $users = DB::table('users')->where('name', 'Ali')->get();
|   // $users is a collection (array-like list) of user objects.
|
|   $user = DB::table('users')->where('name', 'Ali')->first();
|   // $user is just ONE user object. You access $user->id, $user->email directly.
|
| Laravel returns an object for first() because it expects you to interact
| with that SINGLE row's columns directly (e.g., $user->id),
| not loop over an array. Returning an array for first() would be awkward:
| users expect arrays when there may be many results, and an object when there is just one.
|
| This distinction improves code clarity and stops bugs:
| - Use get() → foreach ($users as $user) { ... }
| - Use first() → $user->id, $user->email, directly (no loop).
|
| TL;DR: first() returns a single object, not an array, because you only asked for one row,
| and Laravel wants to make it easy for you to access its properties immediately.
|
*/