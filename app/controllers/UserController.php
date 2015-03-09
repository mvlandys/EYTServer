<?php

use Illuminate\Routing\Controller;

class UserController extends Controller
{
    public function loginPage()
    {
        return View::make("login");
    }

    public function login()
    {
        if (!Input::has("username") || !Input::has("password")) {
            return array("error" => "Input Error");
        }

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
        if (!Input::has("username") || !Input::has("password") || !Input::has("email")) {
            return ["errorMsg" => "No Username, Email or Password specified"];
        }

        $userCount = User::all()->count();

        $user                = new User();
        $user->username      = Input::get("username");
        $user->email         = Input::get("email");
        $user->password      = Hash::make(Input::get("password"));
        $user->admin         = ($userCount == 0) ? 1 : Input::get("admin");
        $user->delete        = Input::get("delete");
        $user->cardsort      = Input::get("cardsort");
        $user->fishshark     = Input::get("fishshark");
        $user->mrant         = Input::get("mrant");
        $user->questionnaire = Input::get("questionnaire");
        $user->vocab         = Input::get("vocab");
        $user->notthis       = Input::get("notthis");
        $user->save();

        if ($userCount == 0) {
            return ["redirect" => "/logout"];
        }

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
        $permissions = array();
        $userPerms   = UserPermissions::where("user_id", "=", $user_id)->get();

        foreach($userPerms as $perm) {
            $permissions[] = $perm->test_name;
        }

        return View::make("users/view_user", array(
            "user"  => User::find($user_id),
            "perms" => $permissions,
            "tests" => $this->getAllTestNames()
        ));
    }

    public function updateUser($user_id)
    {
        $user                = User::find($user_id);
        $user->email         = Input::get("email");
        $user->admin         = Input::get("admin");
        $user->delete        = Input::get("delete");
        $user->cardsort      = Input::get("cardsort");
        $user->fishshark     = Input::get("fishshark");
        $user->mrant         = Input::get("mrant");
        $user->questionnaire = Input::get("questionnaire");
        $user->vocab         = Input::get("vocab");
        $user->notthis       = Input::get("notthis");
        $user->ecers         = Input::get("ecers");

        if (!empty(Input::get("password"))) {
            $user->password = Hash::make(Input::get("password"));
        }

        $user->save();

        if (!empty(Input::get("perms"))) {
            UserPermissions::where("user_id", "=", $user_id)->delete();

            foreach (Input::get("perms") as $perm) {
                $userPerm            = new UserPermissions();
                $userPerm->user_id   = $user_id;
                $userPerm->test_name = $perm;
                $userPerm->save();
            }
        }

        return ["success" => 1];
    }

    public function requestPasswordReset()
    {
        return View::make("users/password_reset");
    }

    public function submitPasswordResetRequest()
    {
        if (!Input::has("email")) {
            return ["error" => "Please enter an email address"];
        }

        $user = User::where("email", "=", Input::get("email"))->first();

        if (empty($user)) {
            return ["error" => "No user account with that email address exists"];
        }

        $resetCode = $this->generateRandomString();
        $userReset = new UserPasswordReset();
        $expires   = new DateTime();
        $expires->add(DateInterval::createFromDateString("1 hour"));

        $userReset->user_id    = $user->id;
        $userReset->reset_code = $resetCode;
        $userReset->expires_at = $expires->format("Y-m-d H:i:s");
        $userReset->save();

        Mail::send("users/password_reset_email", ["user" => $user, "code" => $resetCode], function ($message) use ($user) {
            $message->to($user->email);
        });

        return ["success" => true];
    }

    public function resetPassword($code)
    {
        $reset = UserPasswordReset::where("reset_code", "=", $code)->where("expires_at", ">", date("Y-m-d H:i:s"))->first();

        if (empty($reset)) {
            return View::make("alert", ["type" => "danger", "msg" => "This reset code has expired"]);
        } else {
            return View::make("users/password_reset_submit", ["code" => $code]);
        }
    }

    public function processResetPassword()
    {
        if (!Input::has("code") || !Input::has("password")) {
            return ["error" => "Form Error"];
        }

        $code  = Input::get("code");
        $reset = UserPasswordReset::where("reset_code", "=", $code)->where("expires_at", ">", date("Y-m-d H:i:s"))->first();

        if (empty($reset)) {
            return ["error" => "This reset code has expired"];
        }

        $user           = User::find($reset->user_id);
        $user->password = Hash::make(Input::get("password"));
        $user->save();
        $reset->delete();

        Session::flash("alert", "Successfully changed password");

        return ["success" => true];
    }

    private function generateRandomString($length = 10)
    {
        $characters   = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public function getAllTestNames()
    {
        $testNames = array();
        $tests     = array();
        $games     = array(
            new CardSortGame(),
            new FishSharkGame(),
            new MrAntGame(),
            new NotThisGame(),
            new VocabGame()
        );

        foreach ($games as $game) {
            $tests = array_merge($tests, $game::groupBy("test_name")->get(["test_name"])->toArray());
        }

        foreach ($tests as $val) {
            $testNames[$val["test_name"]] = $val["test_name"];
        }

        $ecers = EcersEntry::groupBy("study")->get(["study"])->toArray();
        foreach ($ecers as $entry) {
            $testNames[$entry["study"]] = $entry["study"];
        }

        ksort($testNames);

        return $testNames;
    }
}