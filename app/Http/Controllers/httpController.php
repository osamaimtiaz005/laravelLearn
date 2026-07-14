<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;
/*
|--------------------------------------------------------------------------
| Explanation: HTTP Client, API, Response, Http, Illuminate, Facade (Laravel)
|--------------------------------------------------------------------------
|
| 1. What is an HTTP Client?
|    - An HTTP Client is a tool or library that allows your application (the client)
|      to send HTTP requests (like GET, POST) to other servers or APIs over the Internet.
|    - In Laravel, the HTTP client (Illuminate\Support\Facades\Http) makes it easy 
|      to call external APIs, fetch data, or send data to other applications.
|
| 2. What is an API?
|    - API stands for Application Programming Interface.
|    - In web development, an API typically refers to a set of endpoints (URLs) that 
|      expose data or functionality which can be accessed (usually by HTTP requests) 
|      from other applications or services.
|    - E.g. "https://jsonplaceholder.typicode.com/users" is a fake API that returns
|      a list of users as JSON — it mimics how a real service would respond.
|   |--------------------------------|--------------------------------|
|       HTTP Client             ||	API
|       Sends requests	        ||  Receives requests
|       Runs in your app        || 	Runs on the server
|       Examples: fetch, Axios	||	Laravel API, Express API
|       Calls endpoints	        ||	Provides endpoints
|   |--------------------------------|--------------------------------|
| 3. What is Response here?
|    - The variable $response represents the reply returned by the external API 
|      after your code sends an HTTP request (with Http::get).
|    - It contains the raw response (headers, status, body), plus methods to 
|      easily convert it to JSON, array, or string.
|    - "$response->json()" decodes the API's JSON reply into a PHP array.
|
| 4. What is Http?
|    - "Http" is a facade in Laravel.
|    - It provides a simple, static interface for making HTTP requests using
|      Laravel's powerful HTTP client.
|    - Example: Http::get('url') sends a GET request to a URL.
|
| 5. What is Illuminate?
|    - "Illuminate" is the root namespace for all core Laravel components. 
|      (E.g. Illuminate\Support\Facades\Http)
|    - Whenever you see "Illuminate", you're using Laravel's own internal classes.
|
| 6. What is Facade?
|    - In Laravel, a "facade" is a special kind of class that provides a "static-like"
|      interface to underlying complex subsystems (services).
|    - Example: "Http" is a facade for the HTTP client. Instead of creating and 
|      configuring an HTTP client object, you can just call methods directly:
|          Http::get(...), Http::post(...)
|    - Other examples: Cache, DB, Route, Event, etc.
|
|--------------------------------------------------------------------------
*/

// This line defines a new PHP class called "httpController" that extends ("inherits from") Laravel's base "Controller" class.
class httpController extends Controller
{
    // This defines a public method named "index". 
    // It takes one parameter, $response, which is expected to be an instance of the "Response" class.
    public function index(Response $response)
    {
        // This line sends a GET HTTP request to the specified URL (a fake public API that returns user data as JSON).
        // The "Http" facade is used here to perform the request.
        // The result of this call (the API's reply) is assigned back to the $response variable.
        $response = Http::get('https://jsonplaceholder.typicode.com/users/1');

        // This line decodes the body of the response (which is expected to be in JSON format) to a PHP object.
        // $response->body() gives the raw response as a string.
        // json_decode converts that JSON string to a PHP object or array.
        $data = json_decode($response->body());

        // This line returns a view called "httpRes.users".
        // The view receives data in the form of an associative array where 'data' is the key, and $data (the decoded user) is the value.
        // This makes the user data available to the Blade view so it can be displayed in HTML.
        return view('httpRes.users', ['data' => $data]);
    }
}
?>