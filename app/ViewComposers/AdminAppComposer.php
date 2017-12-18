<?php

namespace App\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AdminAppComposer
{

    private $request;
    private $pageTitle;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function compose(View $view)
    {
        return $view->with('nav', $this->generateNav())
            ->with('pageTitle', $this->pageTitle);
    }

    private function generateNav()
    {
        // Routes are automatically prefixed with admin:
        $navigation = [
            // route => [urlSegment2, Title]
            'dashboard' => ['', 'Dashboard'],
            'group:index' => ['group', 'Groups'],
            'parking:index' => ['parking', 'Parkings'],
            'parking-level:index' => ['parking-level', 'All Parking Levels'],
            'user:index' => ['user', 'Users'],
        ];

        $nav = '';
        foreach ($navigation as $route => $data) {
            $selected = false;
            if ($this->request->segment(2) == $data[0]) {
                $this->pageTitle = $data[1];
                $selected = true;
            }

            $nav .= '<a href="'.route('admin:'.$route).'"'.($selected ? ' class="selected"' : null).'>'.$data[1].'</a>';
        }

        return $nav;
    }
}
