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

    public function getDynamicUser(string $name): string
    {
        return 'Hello ' . $name; // this is the dynamic user name that we pass in the URL with the help of {name} in url path and then we pass the value of the name in the method parameter and . dot is used to concatenate the string with the value of the name
    }

    /*
    |--------------------------------------------------------------------------
    | View by Controller
    |--------------------------------------------------------------------------
    | This method is used to return the view by controller page dynamically generated from the route parameters {name} in the URL
    |--------------------------------------------------------------------------
    */
    public function getViewByController(string $name)
    {
        return view('viewbycontroller', ['name' => $name]);
    }
    /*
    |--------------------------------------------------------------------------
    | Admin Folder Login Page
    |--------------------------------------------------------------------------
    | This method is used to return the admin login page
     first we add folder name then blade file name . is used to separate the folder name and the blade file name
     'admin.login' → maps to admin/login.blade.php
     Dot (.) means folder structure:
     admin → folder
     login → file
     so we can access the admin folder and the login blade file by using the view('admin.login')
    |--------------------------------------------------------------------------
    */
    public function getAdminLogin()
    {
        return view('admin.login');
    }
    /*
    |--------------------------------------------------------------------------
    | Added myloop variable  which is a key value pair array  to fruits array to the view file to test the loop in the view file
    |--------------------------------------------------------------------------
    */
    public function getViewArrayData()
    {
        return view('testblade', ['myloop' => ['apple', 'banana', 'cherry']]);
        // myloop is the key and ['apple', 'banana', 'cherry'] is the value
        // we can access the myloop variable in the view file by using the $myloop variable
        // we can use the loop in the view file by using the @foreach directive
        // @foreach($myloop as $fruit)
        // <p>{{ $fruit }}</p>
        // @endforeach
        // this will loop through the myloop array and print the value of the array
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
