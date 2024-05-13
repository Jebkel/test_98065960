<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CityMiddleware
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(Request $request, Closure $next)
    {
        $user_city = session()->get('city');
        if (!is_null($user_city) && is_null($request->route('city'))) {
            return redirect()->to("/$user_city");
        }
        if ($request->route('city', $user_city) !== $user_city) {
            session()->set('city', $user_city);
        }
        return $next($request);
    }
}
