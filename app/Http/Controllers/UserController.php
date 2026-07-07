<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    public function addUser(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $city = $request->input('city');
        echo "Name: " . $name . "<br>";
        echo "Email: " . $email . "<br>";
        echo "City: " . $city . "<br>";
        // echo "User added successfully";
        // here Request is a class that is used to get the input from the form
        // $request is an object of the Request class
        // $request->input('name') is used to get the value of the name input field
        // $request->input('email') is used to get the value of the email input field
        // $request->input('city') is used to get the value of the city input field
        // we can use the $request object to get the input from the form
        // we can use the $request object to validate the input
        // we can use the $request object to redirect the user to the home page
        // we can use the $request object to store the data in the database
    }


    public function storeUserAttributes(Request $request)
    {
        print_r($request->skill); // this will print the array of the skill input field print_r is used to print the array in a readable format
        echo "<br>";
        echo $request->gender; // this will print the value of the gender input field
        echo "<br>";
        echo $request->age; // this will print the value of the age input field
        echo "<br>";
        echo $request->dob; // this will print the value of the dob input field
        echo "<br>";
        echo $request->email; // this will print the value of the email input field
        echo "<br>";
        echo $request->country; // this will print the value of the country input field
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
