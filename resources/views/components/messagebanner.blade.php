<!-- php artisan make:component used to create a new component -->
<!-- it make  a  folder named components in resources/views/components and a file named message-banner.blade.php
 and a class named MessageBanner in app/View/Components/MessageBanner.php -->
<!-- we can use the component in the main view file with the x-message-banner tag (avoid literal angle brackets in comments; Blade still compiles x- tags here) -->
<!-- in app/View/Components/MessageBanner.php we can define the properties of the component e.g public $message = 'Hello World' , $style class and use them in the component file -->
<div class="{{ $class }}">
    <span >{{ $message }}</span>
</div>