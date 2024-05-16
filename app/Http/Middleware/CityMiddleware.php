<?php

namespace App\Http\Middleware;

use App\Models\Area;
use Cache;
use Closure;
use Illuminate\Http\Request;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use URL;

class CityMiddleware
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(Request $request, Closure $next)
    {
        $user_city = session()->get('city', 'moscow');
        if (!$request->has('city') || empty($request->input('city'))) {
            $request->merge(['city' => 'kazan']);
        }
        if (is_null($request->route('city'))) {
            URL::defaults(['city' => $user_city]);
            return redirect()->to("/$user_city");
        }
        if ($request->route('city', $user_city) !== $user_city) {
            session()->put('city', $request->route('city', $user_city));

            $user_city = $request->route('city', $user_city);
        }

        $user_city = Cache::remember('city_' . $user_city, 60 * 60, static function ()
        use ($user_city): Area {
            $result = Area::where('alt_name', $user_city)->first();
            if (!is_null($result) && $result->exists()) {
                return $result;
            }
            return Area::where('alt_name', 'moscow')->first();
        });

        $request->attributes->add(['city' => $user_city]);

        URL::defaults(['city' => $user_city->alt_name]);

        return $next($request);
    }
}
