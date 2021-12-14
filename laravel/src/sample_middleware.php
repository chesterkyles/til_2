<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAccessToPageIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $initParam = null;
        foreach($request->route()->parameters() as $param) {
            // skip for initial route parameter
            if ($initParam === null) {
                $initParam = $param;
                continue;
            }

            // break loop if route parameter is not a Model
            if (!$param instanceof Model) {
                break;
            }

            // check if there's relationship between models
            $relation = lcfirst(class_basename($initParam));
            if ($param->$relation === null ||
                $initParam->id !== $param->$relation->id) {
                abort(403);
            }
            $initParam = $param;
        }
        return $next($request);
    }
}
