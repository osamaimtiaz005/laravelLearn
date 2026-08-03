<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;

/**
 * Route Model Binding demo
 *
 * WHAT: Laravel reads {user} from the URL, finds that User row, and injects it here.
 * WHY:  You do NOT write User::find($id) / findOrFail yourself — Laravel does it.
 *
 * WITHOUT binding (old way):
 *   public function show($id) {
 *       $user = User::findOrFail($id);  // you look up manually
 *   }
 *
 * WITH binding (this file):
 *   public function show(User $user) { ... }  // $user is already loaded
 *
 * HOW it matches:
 *   - Parameter name $user must match the route {user}
 *   - Type-hint User tells Laravel which model to load
 *   - Default: find by primary key (id)
 *   - This route uses {user:name} → find by the `name` column instead
 *
 * If no row is found → Laravel returns 404 automatically (findOrFail behavior).
 *
 * Route: GET /user-route-model-binding/{user:name}
 */
class RouteModelBindingController extends Controller
{
    /**
     * Learning page: explains every binding type and builds example links
     * from a few real users in the database.
     */
    public function index()
    {
        $users = User::query()
            ->select(['id', 'name', 'email'])
            ->orderBy('id')
            ->limit(5)
            ->get();

        return view('route_model_binding.index', compact('users'));
    }

    /**
     * IMPLICIT binding by ID.
     *
     * Route: /rmb/implicit/{user}
     * {user} matches $user and User is type-hinted, so Laravel automatically
     * searches the users table by its route key (id by default).
     */
    public function showImplicit(User $user)
    {
        return response()->json([
            'binding_type' => 'Implicit model binding',
            'searched_by' => $user->getRouteKeyName(),
            'route_key_value' => $user->getRouteKey(),
            'user' => $user->only(['id', 'name', 'email']),
        ]);
    }

    /**
     * EXPLICIT binding registered in AppServiceProvider:
     *
     *   Route::model('explicitUser', User::class);
     *
     * The route parameter {explicitUser} is already a User model when this
     * controller method runs. It uses id unless getRouteKeyName() is changed.
     */
    public function showExplicit(User $explicitUser)
    {
        return response()->json([
            'binding_type' => 'Explicit model binding',
            'registered_with' => "Route::model('explicitUser', User::class)",
            'searched_by' => $explicitUser->getRouteKeyName(),
            'user' => $explicitUser->only(['id', 'name', 'email']),
        ]);
    }

    /**
     * EXTRA MODEL METHODS useful when learning/customizing route binding:
     *
     * getRouteKeyName()         → column used for binding (normally "id")
     * getRouteKey()             → this model's value for that column
     * resolveRouteBinding()     → resolve one URL value manually
     * resolveRouteBindingQuery()→ customize the lookup query
     * resolveChildRouteBinding()→ resolve nested/scoped child models
     *
     * This method only displays safe route-key information; it changes nothing.
     */
    public function bindingDetails(User $user)
    {
        return response()->json([
            'model_class' => $user::class,
            'route_key_name' => $user->getRouteKeyName(),
            'route_key_value' => $user->getRouteKey(),
            'route_parameter_is_model' => $user instanceof User,
            'available_customization_methods' => [
                'getRouteKeyName',
                'resolveRouteBinding',
                'resolveRouteBindingQuery',
                'resolveChildRouteBinding',
            ],
        ]);
    }

    /**
     * $user is NOT just the string from the URL — it is a full User model instance.
     * Example URL: /user-route-model-binding/Ali  → User where name = 'Ali'
     */
    public function show(User $user)
    {
        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ]);
    }

    /**
     * INLINE BLADE (Laravel 9+, works in 12) — render a Blade string without a .blade.php file.
     *
     *   Blade::render($template, $data)
     *     $template = Blade syntax as a normal PHP string
     *     $data     = variables available inside that string
     *     returns   = compiled HTML string
     *
     * Same route model binding as show(): {user:name} → User model injected.
     *
     * WHY inline Blade:
     *   - quick demo / debug output with no view file
     *   - tiny snippets (emails, notifications, generated text)
     *
     * WHY NOT for real pages:
     *   - no IDE highlighting, harder to reuse, compiles to a cached view each time
     *   - normal way stays: return view('folder.file', [...]);
     *
     * NOTE: Blade caches the compiled string in storage/framework/views.
     *       Pass a third argument true to delete that cache after rendering:
     *       Blade::render($template, $data, true)
     *
     * Route: GET /user-route-model-binding-inline/{user:name}
     */
    public function showInline(User $user)
    {
        // Heredoc <<<'BLADE' ... BLADE; keeps {{ }} untouched by PHP (nowdoc = no PHP interpolation)
        $template = <<<'BLADE'
            <h2>Route Model Binding — Inline Blade</h2>

            {{-- $user came from the URL segment, already loaded by Laravel --}}
            <p>Name: {{ $user->name }}</p>
            <p>Email: {{ $user->email }}</p>
            <p>Created: {{ $user->created_at }}</p>

            @if ($user->email_verified_at)
                <p>Status: verified</p>
            @else
                <p>Status: not verified</p>
            @endif

            <p>Bound by column: <strong>{{ $boundBy }}</strong></p>
        BLADE;

        return Blade::render($template, [
            'user' => $user,
            'boundBy' => $user->getRouteKeyName(), // 'id' by default; route uses :name
        ]);
    }
}
