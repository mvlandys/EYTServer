<?php

use Illuminate\Routing\Controller;

class UserController extends Controller {

	public function login()
    {
        if (!Input::has("username") || !Input::has("password")) {
            return array("error" => "Input Error");
        }

        if (Input::get("username") == "admin" && Input::get("password") == "admin") {
            Session::set("user_id", 1);
            return array("error" => false);
        } else {
            return array("error" => "Wrong username or password");
        }
    }

    public function logout()
    {
        Session::flush();
        Session::regenerate();

        return Redirect::to("/login");
    }
}