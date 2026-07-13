<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
class User_dbController extends Controller
{
    public function user_db()
    {
        // Retrieve all records from the 'users' table in the database.
        // DB is a facade provided by Laravel to interact with the database.
        // The table('users') method specifies that we want to work with the 'users' table.
        // The get() method executes the query and fetches all rows as a collection.
        // The returned collection contains each user as an object with table column values as properties.
        return DB::table('users')->get();
    }
}
?>