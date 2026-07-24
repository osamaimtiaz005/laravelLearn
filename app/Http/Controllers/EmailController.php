<?php

namespace App\Http\Controllers;

/**
 * EmailController — handles the simple "send email" demo pages.
 *
 * Routes (see routes/web.php):
 *   GET  /email       → index()  show the HTML form
 *   POST /send-email  → send()   validate input and send via SMTP
 *
 * Mail config lives in .env (MAIL_MAILER, MAIL_HOST, MAIL_USERNAME, ...).
 * For Gmail: MAIL_HOST=smtp.gmail.com and use an App Password (not your normal password).
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeMail;

class EmailController extends Controller
{
    /**
     * Show the "Send Email" form.
     *
     * Returns the Blade view at:
     *   resources/views/email/sendMail.blade.php
     *
     * Dot notation: 'email.sendMail' = folder email / file sendMail.blade.php
     */
    public function index()
    {
        return view('email.sendMail');
    }

    /**
     * Handle the form POST: validate → build Mailable → send → redirect with flash message.
     *
     * Request lifecycle here:
     *   1. Browser POSTs form fields (name, subject, to, from, body) + CSRF token
     *   2. validate() checks rules; on failure Laravel redirects BACK with $errors
     *   3. We create WelcomeMail and call Mail::to(...)->send(...)
     *   4. On success/failure we redirect to the form with a session flash message
     *
     * OTHER Mail facade methods we did NOT use here:
     *
     *   Mail::to($to)->queue(new WelcomeMail(...))
     *     → Put the email on the queue (non-blocking). Needs `php artisan queue:work`.
     *
     *   Mail::to($to)->later(now()->addMinutes(5), new WelcomeMail(...))
     *     → Delay sending by 5 minutes (also uses the queue).
     *
     *   Mail::to($to)->cc($cc)->bcc($bcc)->send(...)
     *     → Add CC / BCC recipients at send-time.
     *
     *   Mail::raw('Plain text body', function ($message) use ($to) {
     *       $message->to($to)->subject('Hi');
     *   });
     *     → Send a quick plain-text email without creating a Mailable class.
     *
     *   Mail::html('<h1>Hi</h1>', function ($message) { ... });
     *     → Same idea but with an HTML string.
     *
     *   Mail::mailer('smtp')->to($to)->send(...)
     *     → Force a specific mailer from config/mail.php (useful with multiple providers).
     *
     *   Mail::fake()  (in tests)
     *     → Prevents real sending during PHPUnit/Feature tests; assert sent with Mail::assertSent().
     */
    public function send(Request $request)
    {
        /*
        | Validation rules:
        |   required  → field must be present and not empty
        |   string    → must be a string
        |   email     → must look like a valid email address
        |   max:N     → max length in characters
        |
        | If validation fails, Laravel automatically redirects back to the form
        | and fills the $errors bag (shown in sendMail.blade.php).
        */
        $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'to' => 'required|email|max:255',
            'from' => 'required|email|max:255',
            // body allowed up to 5000 chars (was max:255 before — too short for real messages)
            'body' => 'required|string|max:5000',
        ]);

        // Read validated (or at least present) input from the request
        $name = $request->input('name');
        $subject = $request->input('subject');
        $to = $request->input('to');       // recipient — used by Mail::to()
        $from = $request->input('from');   // sender — passed into WelcomeMail envelope
        $body = $request->input('body');

        try {
            /*
            | Mail::to($to)     → set the recipient (To header)
            | ->send(Mailable)  → build + deliver immediately via default mailer (smtp)
            |
            | new WelcomeMail(...) passes form data into the mailable constructor.
            |
            | Gmail note: "from" should usually match MAIL_USERNAME / a verified alias,
            | otherwise Gmail may reject or rewrite the From address.
            */
            Mail::to($to)->send(new WelcomeMail($name, $to, $subject, $from, $body));

            // redirect + with() stores a one-time "flash" message in the session
            // The form view reads it with: session('success')
            return redirect()->route('email.index')->with('success', 'Email sent successfully');
        } catch (\Throwable $e) {
            /*
            | Catch ANY error during SMTP send (auth failure, connection refused,
            | invalid host, TLS issues, etc.) and show it on the form instead of
            | a generic 500 error page — helpful while learning/debugging.
            |
            | \Throwable covers both Exception and Error in PHP 7+.
            */
            return redirect()->route('email.index')->with('error', 'Failed to send email: '.$e->getMessage());
        }
    }
}
