<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    private const SUPPORTED = ['ka', 'en', 'de', 'pl', 'es'];

    public function handle(Request $request, Closure $next): Response
    {
        $routeLocale = $request->route('locale');

        if ($routeLocale !== null) {
            if (! in_array($routeLocale, self::SUPPORTED, true) || ! Setting::current()->isLocaleEnabled($routeLocale)) {
                abort(404);
            }

            app()->setLocale($routeLocale);
        } else {
            app()->setLocale(Setting::current()->default_locale ?? 'ka');
        }

        return $next($request);
    }
}
