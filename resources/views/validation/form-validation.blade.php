<h2>Form Validation</h2>

<form action="/validate-form" method="post">
    @csrf
    <div>
        <label for="name">Name</label>
        <input type="text" name="name" id="name"/>
    </div>
    <div>
        <label for="email">Email</label>
        <input type="email" name="email" id="email"/>
    </div>
    <div>
        <label for="password">Password</label>
        <input type="password" name="password" id="password"/>
    </div>
    <div>
        <label for="confirm_password">Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password"/>
    </div>
    <button type="submit">Submit</button>
</form>

<style>
    div {
        margin: 10px;
    }
    label {
        display: block;
        margin-bottom: 5px;
    }
    input {
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    button {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
</style>