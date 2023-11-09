<?

    namespace App\Http\Middleware;

    use DB;
    use Closure;

    class UserLastOnline
    {

        public function handle($request, Closure $next)
        {

            if(!$request->ajax() && !auth()->guest()) {

                $user = auth()->user();
                $update = false;
                $time = now();
    
                if (!$user->last_online_at || $user->last_online_at->diffInMinutes(now()) > 5)
                {
                    $update = true;
                }
    
                if($update) {
                    DB::table("users")
                    ->where("id", auth()->user()->id)
                    ->update(["last_online_at" => $time]);
                }
    
            }

            return $next($request);

        }

    }

?>