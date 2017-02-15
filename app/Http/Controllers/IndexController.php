<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

//Models
use App\Page;
use App\Portfolio;
use App\Service;
use App\People;

class IndexController extends Controller
{
    public function execute(Request $request)
    {
        $pages = Page::all();
        $portfolio = Portfolio::get(['name', 'filter', 'images']);
        $services = Service::where('id', '>', 3)->get();
        $people = People::take(3)->get();
        
        dd($pages);
        
        return view('layouts.site');
    }
}
  