<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
class Customer_dbController extends Controller
{
    //

    public function customerList()
    {
        // Retrieve all records from the 'customers' table in the database
        // using Eloquent's static all() method attached to the Customer model.
        // This returns a collection (array-like object) containing each row as a Customer model instance.
        // The resulting $customers variable holds all customer data,
        // ready to be used (e.g., displayed in a view, further processed, etc).
        // using the new keyword to create a new instance of the Customer model
        $data = new \App\Models\Customer();
        // using the show() method to display the customer list
        //->  arrow operator is used to call the method of the object
        $data->show();

        // using the all() method to retrieve all records from the 'customers' table
        $customers = \App\Models\Customer::all();
        return view('database.customers', ['customers' => $customers]); 
        // return $customers;
    }
}
