{{--
  url('storage/'.$path)  — NO asset()
  $path from controller = "files/xxxxx.webp" (from store('files','public'))
  After php artisan storage:link:
    browser URL → /storage/files/xxxxx.webp
  Do NOT use storage/app/public/... — that is the server folder, not a public URL.
--}}
<h3>{{ $path }}</h3>
<img style="width:300px" src="{{ url('storage/'.$path) }}" alt="Uploaded image">
<p><a href="{{ url('/upload-imageform') }}">Upload another</a></p>
