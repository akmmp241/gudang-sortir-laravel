<?php

namespace App\Http\Middleware;

use App\Services\Session\SessionService;
use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;

class MustNotLoginMiddleware
{
    private SessionService $sessionService;

    /**
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->sessionService = app()->make(SessionService::class);
    }

    public function handle(Request $request, Closure $next)
    {
        $user = $this->sessionService->current();
        if ($user != null) {
            return redirect()->back();
        }
        return $next($request);
    }
}
