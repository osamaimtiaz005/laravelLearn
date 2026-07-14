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
    // specify the table name if it's different from the model name
    protected $table = 'customers';
    function show()
    {
        echo "Customer List";
    }

    /*
    |-------------------------------------------------------------------------------------------  
    | Explanation of: php artisan model:show Customer
    |-------------------------------------------------------------------------------------------
    | The Artisan command "php artisan model:show Customer" is a useful tool provided by Laravel
    | to inspect the structure and details of an Eloquent model (in this case, the "Customer" model)
    | directly from the command line.
    |
    | What it does:
    | -------------
    | - When you run this command, it generates a detailed summary of the model.
    | - It displays information about the model's properties, database table, fillable fields,
    |   relationships, and any associated methods.
    | - This is especially helpful for understanding how your model is connected to your database
    |   (such as table and column mappings, primary keys, relationships to other models, etc.).
    |
    | Why and when to use it:
    | ----------------------
    | - If you don't have direct access to the database or database management tools, 
    |   this command lets you examine the structure and setup of your model classes.
    | - It's useful for debugging, onboarding to a new codebase, or documenting your model logic.
    | - You can quickly double-check what fields or relationships are present on the model without
    |   opening database tools or code editors.
    |
    | Example usage:
    | --------------
    |   php artisan model:show Customer
    | 
    | This will print out detailed information about the Customer model on your terminal.
    | 
    | To summarize: 
    | This command acts as a developer-friendly way to inspect your models' database structure
    | and configuration directly from your command line, which is convenient when you don't have 
    | access to the database itself.
    |-------------------------------------------------------------------------------------------
    */

    /*
    |-------------------------------------------------------------------------------------------  
    | Explanation of: Laravel Tinker
    |-------------------------------------------------------------------------------------------
    | Laravel Tinker is a powerful REPL (Read-Eval-Print Loop) tool included with Laravel.
    | It allows you to interact with your entire Laravel application from the command line
    | in an interactive shell.
    |
    | What it does:
    | -------------
    | - Using Tinker, you can interact with your Eloquent models, perform database queries,
    |   update or delete records, test out PHP code, and inspect objects on the fly.
    | - Tinker uses the PsySH shell under the hood, providing an advanced interactive environment.
    |
    | Why and when to use it:
    | ----------------------
    | - Useful for quickly trying out code snippets or experimenting with your models.
    | - Great for debugging, seeding data, or learning how Eloquent queries work.
    | - You can create, retrieve, update, and delete records interactively without having to
    |   write a migration or controller, or use database GUI tools.
    |
    | Example usage:
    | -------------
    |   php artisan tinker
    |   >>> \App\Models\Customer::all();
    |   >>> $customer = new \App\Models\Customer();
    |   >>> $customer->name = 'John Doe';
    |   >>> $customer->email = 'johndoe@example.com';
    |   >>> $customer->save();
    |   >>> \App\Models\Customer::find(1);
    |   >>> \App\Models\Customer::where('email', 'like', '%@gmail.com')->get();
    |
    |   // You can also use Tinker for all sorts of PHP code:
    |   >>> 2 + 2
    |   => 4
    |
    | In summary:
    | -----------
    | - Tinker is an excellent tool for rapid prototyping and exploring your application's
    |   internals.
    | - It is especially handy for Eloquent model experimentation (like Customer), testing
    |   relationships, and manipulating data directly from the command line.
    |-------------------------------------------------------------------------------------------
    */
}
