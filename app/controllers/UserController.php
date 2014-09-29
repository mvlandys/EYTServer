<?php

use Illuminate\Routing\Controller;

class UserController extends Controller
{

    public function login()
    {
        if (!Input::has("username") || !Input::has("password")) {
            return array("error" => "Input Error");
        }

        if (User::all()->count() > 0) {
            $user = User::where("username", "=", Input::get("username"));

            if ($user->count() == 0) {
                return array("error" => "Username does not exist");
            }

            $user = $user->first();

            if (Hash::check(Input::get("password"), $user->password)) {
                Session::set("user_id", $user->id);
                return array("error" => false);
            } else {
                return array("error" => "Password is incorrect");
            }
        } else {
            if (Input::get("username") == "admin" && Input::get("password") == "admin") {
                Session::set("user_id", 1);
                return array("error" => false);
            } else {
                return array("error" => "Wrong username or password");
            }
        }
    }

    public function logout()
    {
        Session::flush();
        Session::regenerate();

        return Redirect::to("/login");
    }

    public function newUser()
    {
        return View::make("users/new_user");
    }

    public function submitNewUser()
    {
        if (!Input::has("username") || !Input::has("password")) {
            return ["errorMsg" => "No Username or Password specified"];
        }

        $user                = new User();
        $user->username      = Input::get("username");
        $user->password      = Hash::make(Input::get("password"));
        $user->admin         = Input::get("admin");
        $user->delete        = Input::get("delete");
        $user->cardsort      = Input::get("cardsort");
        $user->fishshark     = Input::get("fishshark");
        $user->mrant         = Input::get("mrant");
        $user->questionnaire = Input::get("questionnaire");
        $user->vocab         = Input::get("vocab");
        $user->save();

        return ["success" => 1];
    }

    public function listUsers()
    {
        $users = User::all();

        return View::make("users/list_users", array(
            "users" => $users
        ));
    }

    public function viewUser($user_id)
    {
        $user = User::find($user_id);

        return View::make("users/view_user", array(
            "user" => $user
        ));
    }

    public function updateUser($user_id)
    {

    }
}