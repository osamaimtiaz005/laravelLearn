<div>
    {{--
        419 "Page Expired" = CSRF token mismatch. Common causes:
        1) Missing @csrf in the form (we have it below).
        2) Opening the site as http://127.0.0.1:8000 but posting to http://localhost:8000 (or the reverse).
           Session cookies are per-host — always use ONE address for GET and POST.
        3) Submitting with Postman/fetch without the _token field and session cookie.

        action="/addUser" — leading slash = same host you are on now (not hard-coded localhost from APP_URL).
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