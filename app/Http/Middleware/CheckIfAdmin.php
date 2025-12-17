<?php

namespace App\Http\Middleware;

use App\Helpers\Variable;
use Closure;
use Illuminate\Http\JsonResponse;

class CheckIfAdmin
{
    private function checkIfUserIsAdmin($user)
    {
        return $user->hasRole(Variable::ROLE_ADMIN);
    }

    /**
     * Answer to unauthorized access request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    private function respondToUnauthorizedRequest($request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response('Not authorized', JsonResponse::HTTP_UNAUTHORIZED);
        }

        return redirect()->guest(route('admin.login'));
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->guest()) {
            return $this->respondToUnauthorizedRequest($request);
        }

        if (! $this->checkIfUserIsAdmin(auth()->user())) {
            // If the user is not an admin, show logout page
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'You are logged in as ' . auth()->user()->email . '. Please logout first.',
                    'redirect' => route('admin.logout-required'),
                ], 403);
            }

            // Redirect to logout required page
            return redirect()->route('admin.logout-required')
                ->with('error', 'You are logged in as ' . auth()->user()->email . '. Please logout first to access the admin panel.');
        }

        return $next($request);
    }
}

