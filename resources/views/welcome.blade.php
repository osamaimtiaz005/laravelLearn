<!-- HTML code it is view file where we can write HTML code for frontend in Laravel Framework we call this blade template file
    and we can use Laravel specific syntax to write HTML code like {{ $variable }} to display the value of the variable in the view file
    and we can use Laravel specific syntax to write HTML code like @if($condition) for if condition and 
     @foreach($array as $item) to loop through the array and display the value of the variable in the view file
    we can  render this is browser other files by adding this code in routes/web.php file
    Route::get('/', function () {
        return view('welcome');
    });
    also we can name this without .blade 
    also we can use this in controller file by adding this code in controller file
    class WelcomeController extends Controller {
        public function index() {
            return view('welcome');
        }
    }
    and then we can call this controller in routes/web.php file
    Route::get('/', [WelcomeController::class, 'index']);
    and then we can call this controller in routes/web.php file
    controller is a file that contains the logic of the application and we can call this controller in routes/web.php file
-->
<html>

<head>
    <title>Welcome to my website</title>
</head>

<body>
    <h1>Welcome to my website</h1>
</body>

</html>