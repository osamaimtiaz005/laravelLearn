{{--
  DISPLAY / PREVIEW

  - Last upload meta comes from session('upload') set in store()
  - Preview:
      image → <img src="...">
      video → <video src="...">
      pdf   → iframe or download link
  - extension() helper does NOT exist in Laravel — use pathinfo() or stored extension
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Display Files</title>
    <style>
        body { font-family: Georgia, serif; max-width: 720px; margin: 2rem auto; padding: 0 1rem; line-height: 1.5; }
        .ok { color: #0a7a2f; } .err { color: #a11818; }
        .box { border: 1px solid #ccc; padding: 1rem; margin: 1rem 0; }
        table { border-collapse: collapse; width: 100%; font-size: 0.92rem; }
        th, td { border: 1px solid #ccc; padding: 0.35rem 0.5rem; text-align: left; }
        img, video { max-width: 100%; margin-top: 0.75rem; }
        iframe { width: 100%; height: 420px; border: 1px solid #ccc; margin-top: 0.75rem; }
        a.btn { display: inline-block; padding: 0.35rem 0.7rem; background: #0f4c5c; color: #fff; text-decoration: none; margin-right: 0.35rem; }
        code { background: #f3f3f3; padding: 0.1rem 0.3rem; }
    </style>
</head>
<body>
    <h2>Display Files</h2>

    @if(session('success'))
        <p class="ok">{{ session('success') }}</p>
    @endif
    @if(session('error'))
        <p class="err">{{ session('error') }}</p>
    @endif

    @if($upload)
        <div class="box">
            <h3>Last upload (from session)</h3>
            <p><strong>Original name:</strong> {{ $upload['original_name'] }}</p>
            <p><strong>Stored name:</strong> {{ $upload['stored_name'] }}</p>
            <p><strong>Extension:</strong> {{ $upload['extension'] }}</p>
            <p><strong>Size:</strong> {{ $upload['size_kb'] }} KB ({{ $upload['size'] }} bytes)</p>
            <p><strong>Mime type:</strong> {{ $upload['mime_type'] }}</p>
            <p><strong>Stored path:</strong> <code>{{ $upload['stored_path'] }}</code></p>
            <p><strong>Public URL:</strong> <a href="{{ $upload['url'] }}" target="_blank">{{ $upload['url'] }}</a></p>
            <p class="muted"><strong>Temp path (during upload):</strong> {{ $upload['temp_path'] }}</p>
<!--@@php is a directive that is used to execute PHP code in a template
 $ext is the extension of the file and $name is the name of the file
 in_array is a function that checks if the extension is in the array
 it accept two parameters first is the value to check and second is the array to check in
 if the value is in the array, it will return true otherwise it will return false
-->
            @php $ext = $upload['extension']; $name = $upload['stored_name']; @endphp

            @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                <p>Image preview (<code>response()-&gt;file</code> route):</p>
                <img src="{{ route('uploadFiles.preview', $name) }}" alt="preview">
            @elseif($ext === 'pdf')
                <p>PDF preview:</p>
                <iframe src="{{ route('uploadFiles.preview', $name) }}"></iframe>
                <p><a class="btn" href="{{ route('uploadFiles.download', $name) }}">Download PDF</a></p>
            @elseif(in_array($ext, ['mp4', 'mov', 'avi']))
                <p>Video preview:</p>
                <video src="{{ route('uploadFiles.preview', $name) }}" controls></video>
            @else
                <p>Preview not configured for this type —
                    <a class="btn" href="{{ route('uploadFiles.download', $name) }}">Download</a>
                </p>
            @endif
        </div>
    @else
        <p>No recent upload in session. <a href="{{ route('uploadFiles.form') }}">Upload a file</a></p>
    @endif

    <h3>All files in <code>storage/app/public/files</code></h3>
    @if(count($files))
        <table>
            <tr>
                <th>File</th>
                <th>Actions</th>
            </tr>
            @foreach($files as $path)
                @php $name = basename($path); @endphp
                <tr>
                    <td><code>{{ $name }}</code></td>
                    <td>
                        <a href="{{ route('uploadFiles.preview', $name) }}">Preview</a> |
                        <a href="{{ route('uploadFiles.download', $name) }}">Download</a> |
                        <a href="{{ asset('storage/' . $path) }}" target="_blank">Public URL</a> |
                        <form action="{{ route('uploadFiles.destroy', $name) }}" method="post" style="display:inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    @else
        <p>Folder is empty.</p>
    @endif

    <p style="margin-top:1rem;">
        <a class="btn" href="{{ route('uploadFiles.form') }}">Upload another</a>
    </p>

    <p><small>Need public URLs? Run <code>php artisan storage:link</code> once.</small></p>
</body>
</html>
