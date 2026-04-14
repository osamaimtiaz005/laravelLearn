<h1>User Page</h1>
<h2>The name is: {{ $name }}</h2>
<p>This is the user page dynamically generated from the route parameters {name} in the URL</p>
<!-- in laravel  we use double curly braces to display the value of the variable in the view file -->
<!-- and we can use the e() function to escape the value of the variable in the view file to prevent XSS attacks -->
<!-- in php file we use echo to display the value of the variable in the view file -->
