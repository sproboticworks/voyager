<?php

namespace TCG\Voyager\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Models\Operation;

class VoyagerAdminMiddleware
{

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guest()) {
            $urlLogin = route('voyager.login');
            return redirect()->guest($urlLogin);
            //return auth()->user()->hasPermission('browse_admin') ? $next($request) : redirect('/');
        }

        $routeName = $request->route()->getName();

        $operation = Operation::where('route','=',$routeName)->first();
        if($operation) {
            if(auth()->user()->can('do',$operation))
                return $next($request); 
        }

        if ($request->ajax()) {
            return response('Forbidden Error', 403);
        } else {
            return response(view('voyager::error')->with('operation', $operation)
                    ->with('route', $request->getPathInfo()));
        }
    }
}
