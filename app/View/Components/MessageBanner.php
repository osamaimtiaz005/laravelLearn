<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MessageBanner extends Component
{
    public string $message;
    public string $class;
    /**
 * These variables ($message, $class) come from the Blade view
 * when we use the component like:
 * in tags    x-message-banner message="Hello" class="success" 
 *
 * The constructor is a special function that runs automatically
 * when this component is created (no need to call it manually).
 *
 * Its job is to take the incoming values and store them
 * inside the component so we can use them later in the view.
 *
 * $message → text we want to show (like "Success!" or "Error")
 * $class   → CSS class name (like "success", "error", etc.)
 * this is referenced in the component file resources/views/components/message-banner.blade.php
 * $this->message = $message;
 * → store the incoming message into this component object
 *
 * $this->class = $class;
 * → store the CSS class into this component object
 *
 * After this, both $message and $class can be used
 * inside the Blade file:
 * resources/views/components/message-banner.blade.php
 *
 * In short:
 * Constructor = takes input → saves it → makes it usable in view
 */
    public function __construct(string $message, string $class)
    {
        $this->message = $message;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.messagebanner');
    }
}
