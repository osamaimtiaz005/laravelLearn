<h1>Test Blade</h1>

<!-- rand() is a function to generate a random number -->
<h3>Calling a function in blade</h3>
<p>{{ rand()}}</p>
<!-- we can access the myloop variable in the view file by using the $myloop variable we  use square brackets to access the value of the array -->
<h3>Accessing the array value by index</h3>
<p>{{ $myloop[0] }}</p>
<p>{{ $myloop[1] }}</p>
<p>{{ $myloop[2] }}</p>

<h3>Accessing the array value by foreach loop</h3>
<!-- @ is used to start the blade directive -->
<!-- Directive means a special instruction to the blade compiler -->
<!-- Laravel converts it into normal PHP -->

<!-- @foreach is used to loop through the array -->
<!-- $myloop is the array variable -->
<!-- $fruit is the current value of the array -->
<!-- @endforeach is used to end the blade directive -->
<ul>
    @foreach($myloop as $fruit)
    <li>{{ $fruit }}</li>
    @endforeach
</ul>