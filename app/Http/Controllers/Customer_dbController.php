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
        $customers = \App\Models\Customer::all();
     return view('database.customers', ['customers' => $customers]);
        // return $customers;
    }
}
