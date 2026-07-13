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

    public function validateForm(Request $request)
    {
        //validate is a function  from request class that is used to validate the form data
        // we pass an array to the validate function and the array contains the rules for the form fields
        // required is a rule that is used to validate the required fields
        // min is a rule that is used to validate the minimum length of the field
        // max is a rule that is used to validate the maximum length of the field
        // email is a rule that is used to validate the email address
        // unique is a rule that is used to validate the unique email address
        // same is a rule that is used to validate the confirm password field and it should be the same as the password field
        $request->validate(
            [
                'name' => 'required|min:3|max:10',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8|max:16',
                'confirm_password' => 'required|same:password',
                // Approach 1 (commented): use the Rule class directly — no Service Provider needed
                // here we use new keyword to instantiate the class object  and pass the rule to the validate function
                // 'age' => ['required', new ageLimit],

                // Approach 2 (active): string rule — requires Validator::extend() in AppServiceProvider
                'age' => 'required|ageLimit',
            ],
            [
                'name.required' => 'Name cannot be empty',
                'name.min' => 'Name must be at least 3 characters',
                'name.max' => 'Name must be less than 10 characters',
                'email.required' => 'Email cannot be empty',
                'email.email' => 'Email must be a valid email address',
                'email.unique' => 'Email must be unique',
            ]
        );

        // we can also use the messages array to display the custom messages for the validation errors
        //first in array we add field name and then the rule name and then the custom message
        // it is a key value pair array
        echo "Form validated successfully";
    }
    /*
    |--------------------------------------------------------------------------
    | Named Route with Controller Method — getProduct()
    |--------------------------------------------------------------------------
    | URL:  GET /named-route/product/details/id
    | Name: 'product'  (defined in routes/web.php with ->name('product'))
    |
    | This method DISPLAYS the product page by returning a Blade view.
    |--------------------------------------------------------------------------
    |
    | ── route() vs to_route() vs view() ─────────────────────────────────────
    |
    | 1) route('product')
    |    - Returns a URL string only (does NOT redirect, does NOT render a page).
    |    - Used in Blade for links: <a href="{{ route('product') }}">Go to product</a>
    |    - Example output: "http://localhost:8000/named-route/product/details/id"
    |
    | 2) to_route('product')
    |    - Sends an HTTP redirect response to the named route's URL.
    |    - Browser leaves the current page and loads the target route.
    |    - Shorthand for: return redirect()->route('product');
    |    - Use AFTER an action (save form, login, delete) to send user elsewhere.
    |
    |    Example — redirect from a different method (correct usage):
    |
    |        public function saveProduct(Request $request)
    |        {
    |            // ... save product to database ...
    |            return to_route('product');  // user lands on product page
    |        }
    |
    |    With route parameters (when route has {id} placeholder):
    |
    |        return to_route('user.show', ['id' => 5]);
    |        // same as: return redirect()->route('user.show', ['id' => 5]);
    |
    |    With flash message (data available on next request only):
    |
    |        return to_route('product')->with('success', 'Product saved!');
    |
    | 3) view('named-route.product')
    |    - Renders the Blade file and returns HTML (no redirect).
    |    - Use when THIS method is the one that should show the page.
    |
    | ── Why NOT to_route('product') inside getProduct()? ─────────────────────
    |
    |    getProduct() IS the handler for route 'product'.
    |    If you write return to_route('product') here, Laravel redirects to the
    |    same URL → getProduct() runs again → redirects again → infinite loop.
    |
    |    WRONG (infinite loop):
    |        public function getProduct() {
    |            return to_route('product');
    |        }
    |
    |    CORRECT (show the page):
    |        public function getProduct() {
    |            return view('named-route.product');
    |        }
    |
    | ── Summary ─────────────────────────────────────────────────────────────
    |
    |    route()    → build URL string (for <a href>, forms, APIs)
    |    to_route() → redirect browser to a named route (after an action)
    |    view()     → render a page on the current route (no redirect)
    |--------------------------------------------------------------------------
    */
    public function getProduct()
    {
        return view('named-route.product');
    }

    /*
    |--------------------------------------------------------------------------
    | Step 1 — Show the "Add Product" form (GET request)
    |--------------------------------------------------------------------------
    | URL:  GET /named-route/product/add
    | Name: 'product.add'
    |
    | This method only DISPLAYS the form. No redirect here.
    | User fills the form and submits → POST goes to saveProduct() below.
    |--------------------------------------------------------------------------
    */
    public function showAddProductForm()
    {
        return view('named-route.add-product');
    }

    /*
    |--------------------------------------------------------------------------
    | Step 2 — Handle form submit, then redirect with to_route() (POST request)
    |--------------------------------------------------------------------------
    | URL:  POST /named-route/product/save
    | Name: 'product.save'
    |
    | FLOW:
    |   1. User submits form on /named-route/product/add
    |   2. Laravel calls saveProduct()
    |   3. We read/validate input, do work (here we just echo the name)
    |   4. return to_route('product') → browser is REDIRECTED to route named 'product'
    |   5. getProduct() runs and shows named-route.product view
    |
    | to_route('product') is correct HERE because:
    |   - saveProduct() handles a DIFFERENT route ('product.save')
    |   - After saving, we want to send the user to the product page
    |   - We are NOT redirecting to our own route (no infinite loop)
    |
    | ->with('key', 'value') attaches flash data for the NEXT request only
    |    (available in Blade via session('key')).
    |--------------------------------------------------------------------------
    */
    public function saveProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2|max:50',
        ]);

        $productName = $request->input('name');

        // In a real app you would save to database here, e.g.:
        // Product::create(['name' => $productName]);

        // Redirect to named route 'product' with a one-time success message
        return to_route('product')
            ->with('success', 'Product saved successfully!')
            ->with('product_name', $productName);
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
