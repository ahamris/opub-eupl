<?php

namespace App\Http\Controllers;

use App\Models\StaticPage;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Display a static page by slug.
     */
    public function show(string $slug): View
    {
        $page = StaticPage::where('slug', $slug)
            ->active()
            ->firstOrFail();

        return view('pages.show', compact('page'));
    }
}
