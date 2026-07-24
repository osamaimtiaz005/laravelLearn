{{--
  email/index.blade.php — HTML BODY of the outgoing email.

  Used by WelcomeMail::content() via:  view: 'email.index'

  Variables come from WelcomeMail content()->with([...]):
    $name     — greeting name
    $subject  — subject line (shown inside the body for learning/demo)
    $to       — recipient address
    $email    — sender / From address
    $body     — message typed in the form

  This is NOT a normal web page route — it is rendered only when building the email.
  Tip: for production emails, prefer tables / inline CSS for better client support.
--}}
<h2>Welcome to our website</h2>
<p>Hello {{ $name }},</p>
<p>Subject: {{ $subject }}</p>
<p>To: {{ $to }}</p>
<p>From: {{ $email }}</p>
{{-- {{ $body }} is escaped by default (safe against HTML injection) --}}
<p>{{ $body }}</p>
<p>Thank you for signing up for our newsletter. You can unsubscribe at any time.</p>
<p>Best regards, <br>
    <strong>The Team</strong>
</p>
