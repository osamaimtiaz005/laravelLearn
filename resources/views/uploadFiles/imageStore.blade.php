<h2>Image Store</h2>

@if(session('error'))
    <p style="color:red;">{{ session('error') }}</p>
@endif

<form method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="image" accept="image/*" required>
    <!-- formaction = each button posts to a different URL -->
    <button type="submit" formaction="{{ url('/upload-image') }}">Upload with random name (store)</button>
    <button type="submit" formaction="{{ url('/upload-image-fixed-name') }}">Upload without random name (storeAs)</button>
</form>
