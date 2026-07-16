{{--
  FILE UPLOAD FORM

  MUST HAVE:
    method="post"
    enctype="multipart/form-data"  ← sends file bytes (not just text)
    @csrf
    <input type="file" name="file">  ← name matches validation key 'file'
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Files</title>
    <style>
        body { font-family: Georgia, serif; max-width: 560px; margin: 2rem auto; padding: 0 1rem; line-height: 1.5; }
        .ok { color: #0a7a2f; } .err { color: #a11818; }
        form { display: grid; gap: 0.6rem; margin-top: 1rem; }
        input, button { padding: 0.45rem 0.6rem; font: inherit; }
        button { background: #0f4c5c; color: #fff; border: 0; cursor: pointer; width: fit-content; }
        code { background: #f3f3f3; padding: 0.1rem 0.35rem; }
    </style>
</head>
<body>
    <h2>Upload Files</h2>
    <p>Allowed: images, PDF, short videos. Max <code>10240</code> KB (10 MB).</p>

    @if(session('success'))
        <p class="ok">{{ session('success') }}</p>
    @endif
    @if(session('error'))
        <p class="err">{{ session('error') }}</p>
    @endif

    @if($errors->any())
        <ul class="err">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('uploadFiles.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" required>
        <button type="submit">Upload</button>
    </form>

    <p><a href="{{ route('uploadFiles.display') }}">View uploaded files / preview</a></p>
</body>
</html>
