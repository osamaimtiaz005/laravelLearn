<div>
{{--
    419 "Page Expired" usually means the CSRF token validation failed.

    Common causes:
    1. Missing @csrf inside the form.
    2. Opening the application using one host (http://127.0.0.1:8000)
       but submitting the form to another (http://localhost:8000).
       Laravel sessions are tied to the host, so always use the same URL.
    3. Session expired because the user stayed on the page too long.
    4. Sending a POST request from Postman, JavaScript fetch(), Axios, etc.
       without including the CSRF token and the session cookie.

    Why do we use @csrf?

    - CSRF stands for Cross-Site Request Forgery.
    - It protects authenticated users from malicious websites that try
      to submit forms on their behalf.
    - The @csrf directive generates a hidden input containing a unique token.

    Example generated HTML:

        <input type="hidden" name="_token" value="random_generated_token">

    How Laravel validates it:

    1. Laravel creates a CSRF token and stores it in the user's session.
    2. @csrf places the same token inside the form.
    3. When the form is submitted:
       - Laravel compares the token from the form
       - with the token stored in the session.
    4. If they match, the request is allowed.
    5. If they don't match (or the session is missing), Laravel returns:
       419 Page Expired.

    Testing with Postman:

   Since Postman is NOT a browser,

    it does not automatically send:

    - Session cookies
    - CSRF token

    You need both:

    - A valid session cookie.

    - The matching CSRF token.

    Otherwise Laravel will return: 419 Page Expired

        Many developers disable CSRF only for API routes because APIs usually use:

        - JWT
        - Sanctum
        - Passport
        - Bearer Tokens

        instead of CSRF protection.

    Notes:

    - action="/addUser" submits to the current host, whether you're using
      localhost or 127.0.0.1. Avoid hardcoding different hosts.



   
--}}
    <form action="/addUser" method="post">
        @csrf        <div class="input-container">
            <input type="text" name="name" id="name" class="input" placeholder="Enter your name">
        </div>
        <div class="input-container">
            <input type="email" name="email" id="email" class="input" placeholder="Enter your email">
        </div>
        <div class="input-container">
            <input type="text" name="city" id="city" class="input" placeholder="Enter your city">
        </div>
        <div >
            <button type="submit" class="button">Submit</button>
        </div>

    </form>
</div>
<style> 
        .input-container{
            margin:10px
        }

    .input {
        width: 200px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 10px;
        font-size: 16px;
        font-family: Arial, sans-serif;
        box-sizing: border-box;
        margin-left: auto;
    }
    .button {
        width: 200px;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 10px;
        font-size: 16px;
        font-family: Arial, sans-serif;
        background-color: #007bff;
        color: white;
        cursor: pointer;
    }

</style>