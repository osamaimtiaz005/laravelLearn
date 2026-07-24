<?php

namespace App\Mail;

/**
 * WelcomeMail — a Laravel "Mailable" class.
 *
 * A Mailable is an object that describes ONE email:
 *   - who it is from (envelope)
 *   - subject line (envelope)
 *   - HTML/text body (content / Blade view)
 *   - optional file attachments
 *
 * Flow:
 *   EmailController → Mail::to($email)->send(new WelcomeMail(...))
 *                  → Laravel builds the message using envelope() + content() + attachments()
 *                  → SMTP (configured in .env) delivers it
 *
 * Related files:
 *   - app/Http/Controllers/EmailController.php  (builds & sends this mailable)
 *   - resources/views/email/index.blade.php     (HTML body of the email)
 *   - .env MAIL_* keys                          (SMTP host, username, password)
 */

use Illuminate\Bus\Queueable;
// ShouldQueue is intentionally NOT imported/implemented — see class docblock below.
// use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Extends Mailable so Laravel knows how to turn this class into a real email.
 *
 * Traits used:
 *   - Queueable        → allows delaying / queueing the mail (needed if you implement ShouldQueue)
 *   - SerializesModels → safely serializes Eloquent models if you pass them into the constructor
 *                        (useful when the job is stored in the queue database/redis)
 *
 * OPTIONAL (NOT used here):
 *   implements ShouldQueue
 *     → If you add:  class WelcomeMail extends Mailable implements ShouldQueue
 *     → Mail is pushed to the queue instead of sending immediately.
 *     → Requires a queue worker:  php artisan queue:work
 *     → Your .env already has QUEUE_CONNECTION=database
 *     → Good for slow SMTP so the HTTP request returns fast.
 */
class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    /*
    |--------------------------------------------------------------------------
    | Public properties = data available in the Blade email view
    |--------------------------------------------------------------------------
    | IMPORTANT: Do NOT name these $from, $to, or $subject.
    | Those names are already used internally by Laravel's Mailable class
    | (they expect arrays of addresses, not plain strings). Overwriting them
    | breaks From/To headers — that was one of the original bugs.
    */
    public string $name;         // Display name of the sender / recipient greeting
    public string $recipient;    // Destination email (the "To" address as plain text for the view)
    public string $mailSubject;  // Subject line (renamed away from $subject on purpose)
    public string $sender;       // From email address (renamed away from $from on purpose)
    public string $body;         // Message body text typed in the form

    /**
     * Constructor — called when you write: new WelcomeMail($name, $to, $subject, $from, $body)
     *
     * We only STORE the values here. Actual sending happens later when Laravel
     * calls envelope(), content(), and attachments().
     *
     * @param  string  $name     Person's name (used in greeting + From display name)
     * @param  string  $to       Recipient email address
     * @param  string  $subject  Email subject line
     * @param  string  $from     Sender email (with Gmail SMTP this should match MAIL_USERNAME)
     * @param  string  $body     Main message content
     */
    public function __construct($name, $to, $subject, $from, $body)
    {
        $this->name = $name;
        $this->recipient = $to;
        $this->mailSubject = $subject;
        $this->sender = $from;
        $this->body = $body;
    }

    /**
     * envelope() — defines email HEADERS / metadata (not the body).
     *
     * Used here:
     *   - from:    who the email appears to come from (Address = email + display name)
     *   - subject: what shows in the inbox subject column
     *
     * OTHER Envelope options we did NOT use (examples):
     *
     *   replyTo: [new Address('support@example.com', 'Support')]
     *     → When the user hits "Reply", mail goes to this address instead of From.
     *
     *   cc: ['manager@example.com']
     *     → Carbon copy — visible to all recipients.
     *
     *   bcc: ['archive@example.com']
     *     → Blind carbon copy — hidden from other recipients.
     *
     *   tags: ['welcome', 'onboarding']
     *     → Metadata for providers like Mailgun / Postmark (analytics / filtering).
     *
     *   using: [function ($message) { ... }]
     *     → Low-level hook to tweak the underlying Symfony message object.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            // Address(email, name) → "Name <email@domain.com>" in the From header
            from: new Address($this->sender, $this->name),
            subject: $this->mailSubject,

            // --- unused examples (uncomment to try) ---
            // replyTo: [new Address('noreply@example.com', 'No Reply')],
            // cc: ['cc-person@example.com'],
            // bcc: ['hidden@example.com'],
        );
    }

    /**
     * content() — defines the BODY of the email.
     *
     * Used here:
     *   - view: Blade template path (resources/views/email/index.blade.php → 'email.index')
     *   - with: explicit variables passed into that Blade view
     *
     * Note: Public properties on this class are ALSO auto-shared with the view,
     * but we pass `with:` explicitly so the Blade variable names stay clear
     * (e.g. $subject in the view comes from mailSubject, not a conflicting property).
     *
     * OTHER Content options we did NOT use:
     *
     *   html: 'email.index'
     *     → Same idea as `view:` (alias for HTML Blade template).
     *
     *   text: 'email.index-text'
     *     → Plain-text alternative for clients that block HTML.
     *     → Example file: resources/views/email/index-text.blade.php
     *
     *   markdown: 'email.markdown-welcome'
     *     → Use Laravel Markdown mail components (<x-mail::message>, buttons, panels).
     *     → Nice default styling without writing full HTML/CSS yourself.
     *
     *   htmlString: '<h1>Hello</h1><p>Raw HTML string</p>'
     *     → Skip Blade entirely; pass a raw HTML string (less flexible for layouts).
     */
    public function content(): Content
    {
        return new Content(
            // MUST be 'email.index' — NOT just 'index'
            // 'index' would look for resources/views/index.blade.php (which does not exist)
            view: 'email.index',

            with: [
                'name' => $this->name,
                'subject' => $this->mailSubject,
                'to' => $this->recipient,
                'email' => $this->sender, // Blade uses {{ $email }} as the "From" display
                'body' => $this->body,
            ],

            // --- unused examples ---
            // text: 'email.index-text',
            // markdown: 'email.markdown-welcome',
        );
    }

    /**
     * attachments() — files to attach to the email.
     *
     * We return an empty array = no attachments (not used in this demo).
     *
     * Examples of what you CAN return:
     *
     *   Attachment::fromPath('/path/to/invoice.pdf')
     *       ->as('Invoice.pdf')
     *       ->withMime('application/pdf')
     *
     *   Attachment::fromStorage('reports/monthly.xlsx')   // from storage/app/...
     *
     *   Attachment::fromStorageDisk('s3', 'files/doc.pdf') // from a specific disk
     *
     *   Attachment::fromData(fn () => $binaryPdfContents, 'report.pdf')
     *       ->withMime('application/pdf')
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [
            // Example (not used):
            // Attachment::fromPath(public_path('files/welcome.pdf'))
            //     ->as('Welcome-Guide.pdf')
            //     ->withMime('application/pdf'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Other Mailable methods / helpers we did NOT override
    |--------------------------------------------------------------------------
    |
    | build()  (old Laravel style)
    |   → Older tutorials use public function build() { return $this->view(...)->subject(...); }
    |   → Still works, but envelope()/content()/attachments() is the modern Laravel 9+ style.
    |
    | $this->attach($path) / $this->attachFromStorage($path)
    |   → Fluent helpers sometimes used inside build(); attachments() is preferred now.
    |
    | $this->priority(1)
    |   → Sets email priority header (1 = highest, 5 = lowest). Not always honored by clients.
    |
    | Mail::to()->cc()->bcc()->send($mailable)
    |   → Recipients are usually set on the Mail facade in the controller, not inside the mailable.
    |   → That is what EmailController does: Mail::to($to)->send(new WelcomeMail(...))
    |
    | Mail::to($to)->queue(new WelcomeMail(...))
    |   → Queue without implementing ShouldQueue on the class itself.
    |
    | Mail::to($to)->later(now()->addMinutes(10), new WelcomeMail(...))
    |   → Schedule the email to send later via the queue.
    */
}
