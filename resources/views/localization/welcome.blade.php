<p>
    <a href="{{ route('localization.welcome', 'en') }}">English</a>
    |
    <a href="{{ route('localization.welcome', 'ur') }}">اردو</a>
</p>

<p>Session locale: {{ session('locale') }}</p>

<h1>{{ __('welcome.heading') }}</h1>
<h2>{{ __('welcome.subheading') }}</h2>
<h3>{{ __('welcome.description') }}</h3>
