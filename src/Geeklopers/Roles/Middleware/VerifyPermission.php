<?php

namespace Geeklopers\Roles\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Geeklopers\Roles\Exceptions\PermissionDeniedException;

class VerifyPermission
{
    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param \Illuminate\Contracts\Auth\Guard $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param int|string $permission
     * @return mixed
     * @throws \Geeklopers\Roles\Exceptions\PermissionDeniedException
     */
    public function handle($request, Closure $next, $permission, $all = false)
    {
        if ($this->auth->check() && $this->auth->user()->can($permission, $all)) {
            return $next($request);
        }

        throw new PermissionDeniedException($permission);
    }
}
