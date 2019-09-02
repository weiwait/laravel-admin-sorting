<?php

namespace Weiwait\Sorting;

use Encore\Admin\Extension;

class Sorting extends Extension
{
    public $name = 'sorting';

    public $views = __DIR__.'/../resources/views';

    public $assets = __DIR__.'/../resources/assets';

    public $menu = [
        'title' => 'Sorting',
        'path'  => 'sorting',
        'icon'  => 'fa-gears',
    ];
}