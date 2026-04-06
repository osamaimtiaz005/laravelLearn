
<?php

// <?php for php code file where we can write php code for backend in Laravel Framework we call this php code file
namespace App\Http\Controllers; // namespace is for the path of the file

use Illuminate\Http\Request; // this is for import the request class from the Illuminate\Http\Request namespace

class UserController extends Controller // this is  Usercontroller class  that inherit base class Controller using extends keyword and extends the Controller class from the App\Http\Controllers namespace
{
    //
     function getUser()
     {
        return "Osama Imtiaz";
     }

}

/*When user hits a URL:
Controller:
- receives request
- runs logic
- gets data (from DB if needed)
- returns response (view or JSON)
*/
//we can  Create controller using cmd php artisan make:controller UserController