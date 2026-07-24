{{--
  sendMail.blade.php — HTML form used to compose and send an email.

  Shown by: EmailController@index  (GET /email)
  Posted to: EmailController@send  (POST /send-email) via route('email.send')

  Flash messages come from the controller:
    ->with('success', ...)  or  ->with('error', ...)
  Validation errors come from failed $request->validate(...).
--}}

<h2>Send Email</h2>

{{-- Success flash (set after Mail::send succeeds) --}}
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

{{-- Error flash (set when SMTP / send throws an exception) --}}
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

{{--
  Validation errors bag ($errors) is always available in Blade when sessions are on.
  Populated automatically if validate() fails and Laravel redirects back.
--}}
@if ($errors->any())
    <div class="alert alert-danger">
        <ul style="margin:0; padding-left:18px; text-align:left;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{--
  method="post" is required for send().
  route('email.send') resolves to /send-email (named route in web.php).
  @csrf is REQUIRED — without it Laravel returns 419 Page Expired.
--}}
<form action="{{ route('email.send') }}" method="post">
    @csrf

    <div class="form-group">
        <label for="name">Name</label>
        {{-- old('name') repopulates the field after a validation failure --}}
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
    </div>

    <div class="form-group">
        <label for="subject">Subject</label>
        <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" required>
    </div>

    <div class="form-group">
        <label for="to">To</label>
        {{-- Recipient address — becomes Mail::to($to) in the controller --}}
        <input type="email" name="to" class="form-control" value="{{ old('to') }}" required>
    </div>

    <div class="form-group">
        <label for="from">From</label>
        {{--
          old('from', 'default') = use old input if present, otherwise the default Gmail.
          With Gmail SMTP, From must match the authenticated account (or a verified alias).
        --}}
        <input type="email" name="from" class="form-control" value="{{ old('from', 'osumvision005@gmail.com') }}" required>
        <small>Must match your Gmail account (or a verified alias).</small>
    </div>

    <div class="form-group">
        <label for="body">Body</label>
        {{-- Textarea content goes between tags (not in a value="" attribute) --}}
        <textarea name="body" class="form-control" required>{{ old('body') }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary">Send Email</button>
</form>

<style>
    .form-group {
        margin-bottom: 10px;
    }
    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .btn-primary {
        background-color: #007bff;
        color: #fff;
    }
    .btn-primary:hover {
        background-color: #0056b3;
        color: #fff;
    }
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
        text-align: center;
    }
    .alert-success p {
        margin: 0;
    }
    .alert-success p:last-child {
        margin-bottom: 0;
    }
    .alert-success p:first-child {
        margin-top: 0;
    }
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
        text-align: center;
    }
    .alert-danger p {
        margin: 0;
    }
    .alert-danger p:last-child {
        margin-bottom: 0;
    }
    .alert-danger p:first-child {
        margin-top: 0;
    }

</style>
