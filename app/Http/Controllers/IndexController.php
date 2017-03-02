<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;

//Models
use App\Page;
use App\Portfolio;
use App\Service;
use App\People;

use Illuminate\Support\Facades\Mail;

class IndexController extends Controller
{
    
    public function execute(Request $request)
    {
        if ($request->isMethod('post')) {
    
            $messages = [
                'required' => "Поле :attribute обязательно к заполнению",
                'email' => "Поле :attribute должно соответствовать email адресу"
            ];
            
            $this->validate($request, [
                'name' => 'required|max:255',
                'email' => 'required|email',
                'text' => 'required'
            ], $messages);
            
            $data = $request->all();
            
            //mail
            $result = Mail::send('site.email', ['data' => $data], function($message) use ($data) {
                $mail_admin = env('MAIL_ADMIN');

                $message->from($data['email'], $data['name']);
                $message->to($mail_admin)->subject('Question');
            });

            if ($result) {
                return redirect()->route('home')->with('status', 'Email is send');
            }


        }
        
        
        $pages = Page::all();
        $portfolios = Portfolio::get(['name', 'filter', 'images']);
        $services = Service::where('id', '>', 3)->get();
        $peoples = People::take(3)->get();
        
        $tags = DB::table('portfolios')->distinct()->lists('filter');
        
        //dd($tags);
        
        $menu = [];
        foreach( $pages as $page) {
            $item = ['title' => $page->name, 'alias' => $page->alias ];
            array_push($menu, $item);
        }
        $item = ['title' => 'Услуги', 'alias' => 'service'];
        array_push($menu, $item);
    
        $item = ['title' => 'Портфолио', 'alias' => 'Portfilio'];
        array_push($menu, $item);
    
        $item = ['title' => 'Команда', 'alias' => 'team'];
        array_push($menu, $item);
    
        $item = ['title' => 'Контакты', 'alias' => 'contact'];
        array_push($menu, $item);
    
        
        return view('site.index', [
            'menu' => $menu,
            'pages' => $pages,
            'services' => $services,
            'portfolios' => $portfolios,
            'peoples' => $peoples,
            'tags' => $tags
        ]);
    }
}
  