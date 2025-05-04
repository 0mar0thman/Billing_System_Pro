<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            // إذا كان المستخدم مسجل الدخول ولكن حالته غير مفعل
            if (Auth::user()->status !== 'مفعل') {
                Auth::logout();
                return redirect()->route('login')->withErrors(['حسابك غير مفعل، الرجاء التواصل مع المسؤول']);
            }
        }

        return $next($request);
    }
}
