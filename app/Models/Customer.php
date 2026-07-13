<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
/*
|--------------------------------------------------------------------------
| Customer Model Explanation
|--------------------------------------------------------------------------
| This is the Customer Eloquent model. Eloquent is Laravel's built-in ORM 
| (Object-Relational Mapping) system that allows you to interact with your 
| database tables as PHP classes.
|
| Why is this model important?
| ---------------------------
| When you use this model in a controller (e.g., App\Http\Controllers\Customer_dbController),
| you are essentially creating an object-oriented way to perform database 
| operations (such as querying, updating, or deleting records) on the 
| 'customers' table (by Laravel's naming convention).
|
| For example, calling:
|     $customers = \App\Models\Customer::all();
| in a controller fetches all records from the 'customers' table as a 
| collection of Customer model objects.
|
| Usage in Controller:
| -------------------
| In your Customer_dbController, you can do the following:
|     use App\Models\Customer;
|     ...
|     public function customerList() {
|         $customers = Customer::all();
|         // $customers now holds all customer data from the database, 
|         // which you can pass to views, manipulate, etc.
|     }
|
| Benefits:
| ---------
| - Cleaner and more maintainable code
| - Easier database queries, relationships, and data manipulation
| - Protects against SQL injection and simplifies data retrieval
|
| If you need to customize which table this model represents, add:
|     protected $table = 'your_custom_table_name';
| If your primary key is different or not 'id', you can specify:
|     protected $primaryKey = 'your_primary_key';
|--------------------------------------------------------------------------
*/

class Customer extends Model
{
    //
}
