<?php

namespace App\View\Components\Frontend;

use Illuminate\View\Component;

class Instructors extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $user, $type;
    public function __construct($user, $type)
    {
        $this->user = $user;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $data['type'] = $this->type;
        $data['user'] = $this->user;
        return view('components.frontend.instructor', $data);
    }
}
