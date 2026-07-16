<?php

/*
|--------------------------------------------------------------------------
| FILE UPLOADS — full topic guide (matches this controller)
|--------------------------------------------------------------------------
|
| 1) HTML FORM REQUIREMENTS
|    method="post"
|    enctype="multipart/form-data"   ← WITHOUT this, files never arrive
|    <input type="file" name="file"> ← name= must match $request->file('file')
|    @csrf                           ← Laravel POST protection
|
| 2) REQUEST HELPERS
|    $request->hasFile('file')   → true if a file was sent
|    $request->file('file')      → UploadedFile object (or null)
|    $request->file('photos')    → can be an array if input name="photos[]"
|
| 3) VALIDATION (protect your server)
|    required   → field must be present
|    file       → must be an uploaded file
|    image      → jpeg/png/bmp/gif/svg/webp
|    mimes:pdf,jpg,png  → only these extensions/types
|    max:10240  → max size in KILOBYTES (10240 = 10 MB)
|
| 4) UploadedFile METHODS (real ones)
|    getClientOriginalName()      → name from user's PC (e.g. photo.jpg)
|    getClientOriginalExtension() → jpg / pdf / png
|    getSize()                    → bytes
|    getMimeType()                → image/jpeg, application/pdf, …
|    getRealPath()                → temp path PHP saved the upload to
|    isValid()                    → upload had no PHP error
|    store('folder', 'disk')      → save with random name, return path
|    storeAs('folder', 'name')    → save with YOUR chosen name
|
|    NOT real UploadedFile methods (your old code):
|      getUrl() getThumbnail() getPreview()  ← do not exist
|    Build URL yourself with Storage::url() or asset('storage/...')
|    Thumbnail/preview = show <img> / <video> / download link in Blade.
|
| 5) WHERE FILES ARE SAVED
|    storage/app/private/...   → "local" disk (not public URL by default)
|    storage/app/public/...    → "public" disk (web can read AFTER link)
|
|    php artisan storage:link
|      Creates:  public/storage  →  storage/app/public
|      So browser URL /storage/files/x.jpg works.
|
| 6) Storage FACADE
|    Storage::disk('public')->put(...)
|    Storage::disk('public')->url($path)
|    Storage::disk('public')->exists($path)
|    Storage::disk('public')->delete($path)
|
| 7) RESPONSES
|    response()->download($path) → force browser "Save as"
|    response()->file($path)     → show inline (image/pdf in browser)
|
| 8) SECURITY TIPS
|    - Always validate mimes + max size
|    - Prefer store() random names (avoids overwrite / weird filenames)
|    - Do not trust getClientOriginalExtension() alone for security
|    - Keep private docs on "local" disk; only public assets on "public"
|
|--------------------------------------------------------------------------
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadFileController extends Controller
{
public function imageStore(Request $request)
{
// Validate the uploaded image before storing
$request->validate([
    'image' => 'required|file|mimes:jpg,jpeg,png,gif,webp|max:5120', // max 5MB
]);

if (!$request->hasFile('image') || !$request->file('image')->isValid()) {
    return response()->json(['error' => 'No valid image uploaded.'], 400);
}

// Store the image in the public disk under "files/" and return its path
// The store('files', 'public') method does two main things:
//
// 1. The first parameter ('files'):
//    - Specifies the directory within the disk where the file will be saved.
//    - Here, 'files' means all uploads go into the files/ subfolder of the chosen disk.
//
// 2. The second parameter ('public'):
//    - Indicates which filesystem disk to use, based on config/filesystems.php.
//    - 'public' maps to storage/app/public, which is web-accessible if 'php artisan storage:link' has been run.
//
// The method generates a random filename and saves the file at: storage/app/public/files/[randomname.ext]
// random name so we can save no issue of same name 
// If you omit the 2nd argument, Laravel uses the default disk.
// In your config:filesystems.php
// Lines 33-36
//         'local' => [
//             'driver' => 'local',
//             'root' => storage_path('app/private'),
// Default disk is local → root is storage/app/private.

// So:

// ->store('public')
// means:

// storage/app/private + /public + /random.webp
// → storage/app/private/public/xxxxx.webp

    // store() returns path relative to the public disk, e.g. "files/xxxxx.webp"
    // Pass FULL path — Blade builds: url('storage/'.$path) → /storage/files/xxxxx.webp
    $path = $request->file('image')->store('files', 'public');

    return view('uploadFiles.displayImage', ['path' => $path]);
}
    /*
    |--------------------------------------------------------------------------
    | withoutRandomName — storeAs() with YOUR chosen file name
    |--------------------------------------------------------------------------
    | store()     → random name: files/abc123xyz.webp
    | storeAs()   → you pick name: files/dummy.webp
    |
    | storeAs('folder', 'filename.ext', 'disk')
    |   1) folder  under the disk
    |   2) exact file name (overwrite if same name uploaded again)
    |   3) disk    'public' → storage/app/public/...
    |
    | IMPORTANT: use double quotes "dummy.$ext" so PHP inserts $ext.
    | Single quotes 'dummy.$ext' keep the dollar signs as plain text.
    |--------------------------------------------------------------------------
    */
    public function withoutRandomName(Request $request)
    {
        $request->validate([
            'image' => 'required|file|mimes:jpg,jpeg,png,gif,webp|max:5120',
        ]);

        if (! $request->hasFile('image') || ! $request->file('image')->isValid()) {
            return redirect()
                ->to(url('/upload-imageform'))
                ->with('error', 'No valid image uploaded.');
        }

        // extension() → jpg, png, webp, … (guessed from content)
        $fileExtension = $request->file('image')->extension();

        // "dummy.$fileExtension" → dummy.jpg  (NOT 'dummy.$fileExtension')
        // 3rd arg 'public' → storage/app/public/files/dummy.jpg
        $path = $request->file('image')->storeAs(
            'files',
            "dummy.{$fileExtension}",
            'public'
        );

        // $path like "files/dummy.webp" → url('storage/'.$path) works in Blade
        return view('uploadFiles.displayImage', ['path' => $path]);
    }
    /*
    |--------------------------------------------------------------------------
    | showForm() — GET upload page
    |--------------------------------------------------------------------------
    */
    public function showForm()
    {
        return view('uploadFiles.store');
    }

    /*
    |--------------------------------------------------------------------------
    | store(Request $request) — handle the upload (POST)
    |--------------------------------------------------------------------------
    |
    | Flow:
    |   1. Validate
    |   2. Read UploadedFile info (name, size, mime…)
    |   3. store() on public disk under files/
    |   4. Flash success + redirect to display page with meta in session
    |
    |--------------------------------------------------------------------------
    */
    
    public function store(Request $request)
    {
        // Validate BEFORE using the file
        $request->validate([
            // 'file' = input name from <input type="file" name="file">
            'file' => 'required|file|mimes:jpg,jpeg,png,gif,webp,pdf,mp4,mov,avi|max:10240',
        ]);

        // Guard: was a real file uploaded?
        if (! $request->hasFile('file') || ! $request->file('file')->isValid()) {
            return redirect()
                ->route('uploadFiles.form')
                ->with('error', 'No valid file uploaded');
        }

        /*
        | $file is an Illuminate\Http\UploadedFile instance
        | (extends PHP's SplFileInfo with upload helpers)
        */
        $file = $request->file('file');

        // ---- Info about the upload (before / while storing) ----
        $originalName = $file->getClientOriginalName();      // e.g. report.pdf
        $extension = strtolower($file->getClientOriginalExtension()); // pdf
        $sizeBytes = $file->getSize();                       // bytes
        $mimeType = $file->getMimeType();                    // application/pdf
        $tempPath = $file->getRealPath();                    // PHP temp location

        /*
        | store('files', 'public')
        |   folder = files  → storage/app/public/files/...
        |   disk   = public → publicly linkable via /storage/...
        |   returns path like:  files/abc123xyz.pdf  (random name — safe)
        |
        | Old code used store('public/files') on default disk — confusing.
        | Prefer: store(folder, diskName)
        */
        $storedPath = $file->store('files', 'public');

        // Public URL after: php artisan storage:link
        // Use asset() so XAMPP subdirectory works (/firstLearning/public)
        // Example: http://localhost/firstLearning/public/storage/files/abc123.pdf
        $publicUrl = asset('storage/' . $storedPath);

        // Basename only (for download route param)
        $storedFileName = basename($storedPath);

        /*
        | Put display data in SESSION (flash) — cleaner than huge query strings.
        | Your old redirect(..., $data) as route params does not work that way.
        */
        return redirect()
            ->route('uploadFiles.display')
            ->with('success', 'File uploaded successfully')
            ->with('upload', [
                'original_name' => $originalName,
                'stored_name' => $storedFileName,
                'stored_path' => $storedPath,
                'extension' => $extension,
                'size' => $sizeBytes,
                'size_kb' => round($sizeBytes / 1024, 2),
                'mime_type' => $mimeType,
                'temp_path' => $tempPath,
                'url' => $publicUrl,
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | display() — show last upload info + preview
    |--------------------------------------------------------------------------
    */
    public function display()
    {
        $upload = session('upload');

        // Also list every file currently in storage/app/public/files

        // Storage::disk('public')->files('files') is used to get all the files in the public directory
        // print_r is used to print the array
        $files = Storage::disk('public')->files('files');
        print_r($files);
        return view('uploadFiles.display', [
            'upload' => $upload,
            'files' => $files,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | download($fileName) — force download (Save As)
    |--------------------------------------------------------------------------
    | response()->download(absolutePath)
    | Real life: PDF invoice, ZIP, DOCX
    |--------------------------------------------------------------------------
    */
    public function download(string $fileName)
    {
        $path = 'files/' . $fileName;
        // Storage::disk('public')->exists($path) is used to check if the file exists in the public directory
        if (! Storage::disk('public')->exists($path)) {
            return redirect()
                ->route('uploadFiles.display')
                ->with('error', 'File not found');
        }

        // Absolute filesystem path for download()
        // Storage::disk('public')->path($path) is used to get the absolute filesystem path for the file
        // response()->download is used to download the file
        return response()->download(
            Storage::disk('public')->path($path),
            $fileName
        );
    }

    /*
    |--------------------------------------------------------------------------
    | preview($fileName) — show file inline in browser
    |--------------------------------------------------------------------------
    | response()->file() → browser displays image / pdf / video
    | (Your old thumbnail() + preview() were the same idea)
    |--------------------------------------------------------------------------
    */
    public function preview(string $fileName)
    {
        $path = 'files/' . $fileName;

        if (! Storage::disk('public')->exists($path)) {
            abort(404, 'File not found');
        }

        return response()->file(Storage::disk('public')->path($path));
    }

    /*
    |--------------------------------------------------------------------------
    | destroy($fileName) — delete from disk
    |--------------------------------------------------------------------------
    */
    public function destroy(string $fileName)
    {
        $path = 'files/' . $fileName;

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        return redirect()
            ->route('uploadFiles.display')
            ->with('success', 'File deleted: ' . $fileName);
    }
}
