<h2>Conditional Middleware Demo</h2>

<p><em><code>globalMid</code> runs only on student routes via <code>->middleware('global.mid')</code>.</em></p>

<p>This page has <strong>no</strong> route middleware.</p>

<h3>Public route (no route middleware)</h3>
<p><a href="{{ url('/middleware-demo/public') }}">/middleware-demo/public</a></p>

<h3>Protected route (middleware: access.key)</h3>
<p>
  Without access key:
  <a href="{{ url('/middleware-demo/protected') }}">/middleware-demo/protected</a> → 403
</p>
<p>
  With access key:
  <a href="{{ url('/middleware-demo/protected?access_key=learn123') }}">/middleware-demo/protected?access_key=learn123</a> → allowed
</p>

<h3>Student routes — global.mid on entire group</h3>
<ul>
  <li><a href="{{ url('/show') }}">/show</a> — runs globalMid</li>
  <li><a href="{{ url('/edit') }}">/edit</a> — runs globalMid</li>
  <li><a href="{{ url('/delete') }}">/delete</a> — runs globalMid</li>
</ul>
