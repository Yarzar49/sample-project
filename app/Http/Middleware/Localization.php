<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!empty(session('locale'))){
        $locale = $request->session()->get('locale', config('app.locale'));

        // Set the application locale
        App::setLocale($locale);
        }else{
            session()->put('locale','en');
            App::setLocale(session('locale'));
        }
        return $next($request);
    }
}
