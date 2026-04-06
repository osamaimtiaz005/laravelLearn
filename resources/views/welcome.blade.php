{{-- Blade reads the whole file: @if / @foreach in comments are still parsed and need @endif.
     @verbatim ... @endverbatim = show this block as plain text; Blade ignores @ and {{ inside. --}}
@verbatim
<!-- HTML code: this is a view file where we write HTML for the frontend. In Laravel we call this a Blade template.
    We can use Laravel syntax like {{ $variable }} to print a variable, @if($condition) for conditions, and
    @foreach($array as $item) to loop. (Inside @verbatim, these are documentation only — not executed.)

    Render in the browser from routes/web.php:
    Route::get('/', function () {
        return view('welcome');
    });

    The view name is 'welcome' (no .blade in the name). In a controller:
    class WelcomeController extends Controller {
        public function index() {
            return view('welcome');
        }
    }
    Route::get('/', [WelcomeController::class, 'index']);

    A controller holds application logic; the route points the URL to the controller action.
-->
@endverbatim

<h1>Welcome to my website</h1>