<h2>
    User Attributes Form
</h2>
<form action="/store-user-attributes" method="post">
    @csrf
    <div>

        <!-- skill checkbox -->
        <!-- for array input, we use [] in the name attribute so that we can store multiple values in the array -->
        <!-- for example, if we want to store multiple skills, we can use skill[] in the name attribute -->
        <!-- id and for is used to bind the checkbox with the label on text click it will check the checkbox -->
    <input type="checkbox" name="skill[]" value="PHP" id="php"/>
    <label for="php">PHP</label>
    <input type="checkbox" name="skill[]" value="Laravel" id="laravel"/>
    <label for="laravel">Laravel</label>
    <input type="checkbox" name="skill[]" value="CodeIgniter" id="codeigniter"/>
    <label for="codeigniter">CodeIgniter</label>
    <input type="checkbox" name="skill[]" value="Symfony" id="symfony"/>
    <label for="symfony">Symfony</label>
    <input type="checkbox" name="skill[]" value="CakePHP" id="cakephp"/>
    <label for="cakephp">CakePHP</label>
    </div>

    <div>
        <input type="radio" name="gender" value="male" id="male"/>
        <label for="male">Male</label>
        <input type="radio" name="gender" value="female" id="female"/>
        <label for="female">Female</label>
        <input type="radio" name="gender" value="other" id="other"/>
        <label for="other">Other</label>
    </div>
    <div>
        <input type="range" name="age" id="age" min="18" max="100"/>
        <label for="age">Age</label>
    </div>
    <div>
        <input type="date" name="dob" id="dob"/>
        <label for="dob">Date of Birth</label>
    </div>
    <div>
        <input type="email" name="email" id="email"/>
        <label for="email">Email</label>
    </div>

    <div>
        <select name="country" id="country">
            <option value="pakistan">Pakistan</option>
            <option value="usa">USA</option>
            <option value="uk">UK</option>
            <option value="canada">Canada</option>
            <option value="australia">Australia</option>
            <option value="newzealand">New Zealand</option>
            <option value="southafrica">South Africa</option>
        </select>
    </div>
    <button type="submit">Submit</button>
</form>
<style>
    div {
        margin: 10px;
    }
</style>