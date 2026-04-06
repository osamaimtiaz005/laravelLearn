<h1>Test Page</h1>
<p>This is a test page it still runs in view file as we havent named it with .blade extension but it will consider as
    php file and we can use php code in this file to display the data in the browser
    but blade template file is more powerful and easier to use and we can use blade template file to display the data in the browser
</p>



<!-- Normal PHP file code example: -->
    <!-- <?php foreach ($users as $user): ?> -->
<!-- <p><?php echo $user->name; ?></p> -->
<!-- <?php endforeach; ?> -->

<!-- 😵 Hard to read -->
<!-- 😵 Ugly syntax -->

<!-- ✅ Blade file code example: -->
<!-- @foreach($users as $user)
 <p>{{ $user->name }}</p> -->
<!-- @endforeach -->

<!-- 😍 Clean -->
<!-- 😍 Easy -->
