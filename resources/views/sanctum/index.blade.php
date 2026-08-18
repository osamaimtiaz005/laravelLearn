<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sanctum Auth APIs — Laravel 12</title>
    <style>
        body { max-width: 1000px; margin: 30px auto; padding: 0 18px; font: 16px/1.55 Arial, sans-serif; color: #1f2937; }
        h1, h2 { color: #111827; }
        section { margin: 24px 0; padding: 18px; border: 1px solid #d1d5db; border-radius: 8px; }
        code, pre { background: #f3f4f6; border-radius: 5px; }
        code { padding: 2px 5px; }
        pre { padding: 14px; overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 9px; border: 1px solid #d1d5db; text-align: left; vertical-align: top; }
        th { background: #f3f4f6; }
        a { color: #0369a1; }
        .note { color: #92400e; background: #fef3c7; padding: 10px; border-radius: 6px; }
        .ok { color: #065f46; background: #d1fae5; padding: 10px; border-radius: 6px; }
        ol li { margin: 6px 0; }
    </style>
</head>
<body>
    <h1>Sanctum — login, signup, auth APIs (User)</h1>

    <p>
        <strong>Sanctum</strong> issues API tokens for the <code>User</code> model.
        Postman / mobile apps send <code>Authorization: Bearer {token}</code>.
        This is <em>not</em> the same as web session login on <code>/sessions</code>.
    </p>

    <p class="ok">
        Controller: <code>SanctumController</code> ·
        Trait: <code>HasApiTokens</code> on <code>User</code> ·
        Table: <code>personal_access_tokens</code>
    </p>

    <section>
        <h2>Token flow</h2>
        <ol>
            <li><code>POST /api/auth/register</code> or <code>/api/auth/login</code> → JSON includes <code>token</code></li>
            <li>Copy the token (shown once)</li>
            <li>Postman → Authorization → Bearer Token → paste it</li>
            <li><code>GET /api/auth/me</code> returns the logged-in user</li>
            <li><code>POST /api/auth/logout</code> deletes this token only</li>
        </ol>
    </section>

    <section>
        <h2>Endpoints (User model only)</h2>
        <table>
            <thead>
                <tr><th>Auth?</th><th>Method</th><th>URL</th><th>Action</th></tr>
            </thead>
            <tbody>
                <tr>
                    <td>Public</td>
                    <td>POST</td>
                    <td><code>/api/auth/register</code></td>
                    <td>Signup + token</td>
                </tr>
                <tr>
                    <td>Public</td>
                    <td>POST</td>
                    <td><code>/api/auth/login</code></td>
                    <td>Login + token</td>
                </tr>
                <tr>
                    <td>Bearer</td>
                    <td>GET</td>
                    <td><code>/api/auth/me</code></td>
                    <td>Current user</td>
                </tr>
                <tr>
                    <td>Bearer</td>
                    <td>GET</td>
                    <td><code>/api/auth/tokens</code></td>
                    <td>List token names (not secrets)</td>
                </tr>
                <tr>
                    <td>Bearer</td>
                    <td>POST</td>
                    <td><code>/api/auth/logout</code></td>
                    <td>Revoke this device</td>
                </tr>
                <tr>
                    <td>Bearer</td>
                    <td>POST</td>
                    <td><code>/api/auth/logout-all</code></td>
                    <td>Revoke every device</td>
                </tr>
            </tbody>
        </table>
        <p class="note">
            Try <a href="{{ url('/api/auth/me') }}" target="_blank">/api/auth/me</a> in the browser
            with no token — you should get <strong>401 JSON</strong>, not an HTML login page.
        </p>
    </section>

    <section>
        <h2>Sanctum vs web sessions</h2>
        <table>
            <thead>
                <tr><th></th><th>Web sessions (<code>/sessions</code>)</th><th>Sanctum tokens (this lesson)</th></tr>
            </thead>
            <tbody>
                <tr><td>Client</td><td>Browser</td><td>Postman, mobile, SPA, other servers</td></tr>
                <tr><td>Proof</td><td>Session cookie</td><td><code>Authorization: Bearer ...</code></td></tr>
                <tr><td>CSRF</td><td>Required on POST</td><td>Not used for this token API</td></tr>
                <tr><td>Logout</td><td>Destroy session</td><td>Delete token row(s)</td></tr>
            </tbody>
        </table>
        <p>
            Sanctum can also do <strong>SPA cookie auth</strong> (<code>/sanctum/csrf-cookie</code>).
            This project’s demos use <strong>personal access tokens</strong> only.
        </p>
    </section>

    <section>
        <h2>Postman examples</h2>
@verbatim
<pre>// Always:
Accept: application/json
Content-Type: application/json

// 1) Register
POST /api/auth/register
{
  "name": "Ali",
  "email": "ali.sanctum@example.com",
  "password": "secret123",
  "device_name": "postman"
}

// 2) Login (existing user)
POST /api/auth/login
{
  "email": "ali.sanctum@example.com",
  "password": "secret123",
  "device_name": "postman"
}

// 3) Copy token from the response, then:
Authorization: Bearer {paste-token-here}

GET  /api/auth/me
GET  /api/auth/tokens
POST /api/auth/logout
POST /api/auth/logout-all</pre>
@endverbatim
    </section>

    <section>
        <h2>How auth:sanctum works</h2>
@verbatim
<pre>// routes/api.php
Route::post('/auth/register', [SanctumController::class, 'register']); // public
Route::post('/auth/login',    [SanctumController::class, 'login']);    // public

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [SanctumController::class, 'me']); // needs token
});

// User model
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable;
}

// After login
$plain = $user->createToken('postman')->plainTextToken;
// DB stores a HASH. Client must keep $plain.</pre>
@endverbatim
        <p class="note">
            User password uses the <code>hashed</code> cast. Do not <code>Hash::make()</code> again
            on register or the password is hashed twice and login fails.
        </p>
    </section>

    <section>
        <h2>Key files</h2>
        <ul>
            <li><code>app/Http/Controllers/SanctumController.php</code></li>
            <li><code>app/Models/User.php</code> — <code>HasApiTokens</code></li>
            <li><code>routes/api.php</code> — <code>/api/auth/*</code></li>
            <li><code>config/sanctum.php</code></li>
            <li><code>database/migrations/*_create_personal_access_tokens_table.php</code></li>
        </ul>
        <p>
            Related: <a href="{{ url('/api-learning') }}">/api-learning</a> ·
            <a href="{{ url('/resource-controller') }}">/resource-controller</a> ·
            README section <strong>AE. Sanctum</strong>
        </p>
    </section>
</body>
</html>
