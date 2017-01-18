<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function ($request)
{
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
    header('Access-Control-Allow-Credentials: true');

    Event::listen('illuminate.query', function ($query, $bindings, $time, $name) {
        $data = compact('bindings', 'time', 'name');

        // Format binding data for sql insertion
        foreach ($bindings as $i => $binding) {
            if ($binding instanceof \DateTime) {
                $bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
            } else if (is_string($binding)) {
                $bindings[$i] = "'$binding'";
            }
        }

        // Insert bindings into query
        $query = str_replace(array('%', '?'), array('%%', '%s'), $query);
        $query = vsprintf($query, $bindings);

        //echo $query . "<br/>";

        //Log::info($query, $data);
    });

    if (Session::has("user_id")) {
        App::bind("user", function () {
            return User::find(Session::get("user_id"));
        });

        App::bind('perms', function () {
            $user = App::make("user");

            if ($user->admin == 1) {
                $userController = new UserController();
                $allPerms       = $userController->getAllTestNames();
                $perms          = array();

                foreach ($allPerms as $perm) {
                    if ($perm == "") {
                        $perm = "Untitled Test (.)";
                    }

                    $obj            = new stdClass();
                    $obj->test_name = $perm;
                    $perms[]        = $obj;
                }
            } else {
                $perms = UserPermissions::where("user_id", "=", Session::get("user_id"))->remember(5)->get(["test_name"]);
            }

            if (empty($perms)) {
                $obj            = new stdClass();
                $obj->test_name = "test";
                $perms[]        = $obj;
            }

            return $perms;
        });
    }
});


App::after(function ($request, $response) {
    /*
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');
    header('Access-Control-Allow-Credentials: true');
    */
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function () {
    if (!Session::has("user_id")) {
        return Redirect::to("/login");
    }
});

Route::filter("admin", function () {
    $user = App::make("user");

    if ($user->admin == 0) {
        return Redirect::to("/")->withErrors(['Access Denied']);
    }
});

Route::filter("cardsort", function () {
    $user = App::make("user");

    if ($user->cardsort == 0 && $user->admin == 0) {
        return Redirect::to("/")->withErrors(['Access Denied']);
    }
});

Route::filter("fishshark", function () {
    $user = App::make("user");

    if ($user->fishshark == 0 && $user->admin == 0) {
        return Redirect::to("/")->withErrors(['Access Denied']);
    }
});

Route::filter("vocab", function () {
    $user = App::make("user");

    if ($user->vocab == 0 && $user->admin == 0) {
        return Redirect::to("/")->withErrors(['Access Denied']);
    }
});

Route::filter("questionnaire", function () {
    $user = App::make("user");

    if ($user->questionnaire == 0 && $user->admin == 0) {
        return Redirect::to("/")->withErrors(['Access Denied']);
    }
});

Route::filter("mrant", function () {
    $user = App::make("user");

    if ($user->mrant == 0 && $user->admin == 0) {
        return Redirect::to("/")->withErrors(['Access Denied']);
    }
});

Route::filter("notthis", function () {
    $user = App::make("user");

    if ($user->notthis == 0 && $user->admin == 0) {
        return Redirect::to("/")->withErrors(['Access Denied']);
    }
});

Route::filter("ecers", function () {
    $user = App::make("user");

    if ($user->ecers == 0 && $user->admin == 0) {
        return Redirect::to("/")->withErrors(['Access Denied']);
    }
});

Route::filter("delete", function () {
    $user = App::make("user");

    if ($user->admin == 0 && $user->delete == 0) {
        return Redirect::to("/")->withErrors(['Access Denied']);
    }
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function () {
    if (Session::token() != Input::get('_token')) {
        throw new Illuminate\Session\TokenMismatchException;
    }
});
