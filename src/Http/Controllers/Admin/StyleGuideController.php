<?php

namespace Packages\Appearance\Http\Controllers\Admin;

use Illuminate\Http\Request;
use AdminController;
use Validator;

class StyleGuideController extends AdminController
{
    public function index()
    {
        \Metatag::set('title', 'Style guide');
        $this->authorize('admin', $menu);
        return view('Appearance::admin.style-guide.index', $this->data);
    }
}
