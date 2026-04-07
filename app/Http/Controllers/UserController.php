<?php

namespace App\Http\Controllers;

/**
 * namespace is for the path of the file
 * UserController — handles HTTP logic for user-related routes.
 *
 * Flow: route → controller method → return view / JSON / string.
 *
 * Create controllers: php artisan make:controller UserController
 * 
 * We use public so Laravel can access and execute controller methods from routes
 * 
 */
class UserController extends Controller
{
    public function getUser(): string
    {
        return 'Osama Imtiaz ';
    }
}

/*
When a user hits a URL:
- Controller receives the request
- Runs logic / loads data (e.g. from DB)
- Returns a response (view, JSON, redirect, etc.)
- Types of methods in controller
index()   → list data  
show()    → single item  
store()   → save data  
update()  → update  
delete()  → remove  
*/
