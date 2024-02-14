<?php

namespace App\Http\Middleware;

use App\Models\Course;
use App\Models\Enrollment;
use Closure;
use Illuminate\Http\Request;
use App\Models\Order_item;
use App\Traits\ApiStatusTrait;

class CourseAccessMiddleware
{
    use ApiStatusTrait;
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        $user_id = $user->id;

        $course = Course::whereSlug($request->slug)->select('id', 'user_id')->firstOrfail();
        
        if($course->user_id == $user_id){
            return $next($request);
        }
        
        $enrollment = Enrollment::where(['user_id' => $user_id, 'course_id' => $course->id, 'status' => ACCESS_PERIOD_ACTIVE])->whereDate('end_date', '>=', now())->count();
        
        if(!$enrollment){
            if ($request->wantsJson()) {
                $msg = __("Unauthorize route");
                return $this->error([], $msg, 403);
            } else {
                abort('403');
            }
        }
        
        return $next($request);
    }
}
