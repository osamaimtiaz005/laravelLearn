<h2>Conditional Middleware Demo</h2>

<p><em>Note: your <code>globalMid</code> requires <code>?name=osama</code> on every URL. Links below include it.</em></p>

<p>This page has <strong>no route middleware</strong> — only the global check above applies.</p>

<h3>Public route (no route middleware)</h3>
<p><a href="{{ url('/middleware-demo/public?name=osama') }}">/middleware-demo/public</a></p>

<h3>Protected route (middleware: access.key)</h3>
<p>
  Without access key:
  <a href="{{ url('/middleware-demo/protected?name=osama') }}">/middleware-demo/protected</a> → 403
</p>
<p>
  With access key:
  <a href="{{ url('/middleware-demo/protected?name=osama&access_key=learn123') }}">/middleware-demo/protected?access_key=learn123</a> → allowed
</p>

<h3>Student routes — access.key only on edit &amp; delete</h3>
<ul>
  <li><a href="{{ url('/show?name=osama') }}">/show</a> — open</li>
  <li><a href="{{ url('/edit?name=osama') }}">/edit</a> — blocked without access_key</li>
  <li><a href="{{ url('/edit?name=osama&access_key=learn123') }}">/edit?access_key=learn123</a> — allowed</li>
  <li><a href="{{ url('/delete?name=osama') }}">/delete</a> — blocked without access_key</li>
  <li><a href="{{ url('/delete?name=osama&access_key=learn123') }}">/delete?access_key=learn123</a> — allowed</li>
</ul>
