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
            return ["error" => "Input Error"];
        }

        $user = User::where("username", "=", Input::get("username"));

        if ($user->count() == 0) {
            return ["error" => "Username does not exist"];
        }

        $user = $user->first();

        if (Hash::check(Input::get("password"), $user->password)) {
            Session::set("user_id", $user->id);

            return ["error" => false];
        } else {
            return ["error" => "Password is incorrect"];
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
        $user->ecers         = Input::get("ecers");
        $user->rde           = Input::get("rde");
        $user->earlynum      = Input::get("earlynum");
        $user->save();

        if ($userCount == 0) {
            return ["redirect" => "/logout"];
        }

        return ["success" => 1];
    }

    public function listUsers()
    {
        $users = User::all();

        return View::make("users/list_users", ["users" => $users]);
    }

    public function viewUser($user_id)
    {
        $permissions = [];
        $userPerms   = UserPermissions::where("user_id", "=", $user_id)->get();
        $tests       = $this->getAllTestNames();

        foreach ($userPerms as $perm) {
            if ($perm->test_name == "") {
                $perm->test_name = "Untitled Test (.)";
            }

            $permissions[] = $perm->test_name;
        }

        foreach ($tests as $key => $val) {
            if ($val == "") {
                $tests[$key] = "Untitled Test (.)";
            }
        }

        return View::make("users/view_user", ["user"  => User::find($user_id),
                                              "perms" => $permissions,
                                              "tests" => $tests]);
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
        $user->rde           = Input::get("rde");
        $user->earlynum      = Input::get("earlynum");

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

        Mail::send("users/password_reset_email", ["user" => $user,
                                                  "code" => $resetCode], function($message) use ($user) {
            $message->to($user->email);
        });

        return ["success" => true];
    }

    public function resetPassword($code)
    {
        $reset = UserPasswordReset::where("reset_code", "=", $code)->where("expires_at", ">", date("Y-m-d H:i:s"))->first();

        if (empty($reset)) {
            return View::make("alert", ["type" => "danger",
                                        "msg"  => "This reset code has expired"]);
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
        $testNames = [];
        $tests     = [];
        $games     = [new CardSortGame(),
                      new FishSharkGame(),
                      new MrAntGame(),
                      new NotThisGame(),
                      new VocabGameNew(),
                      new VerbalGame(),
                      new NumbersGame(),
                      new NumeracyGame(),
                      new EarlyNumeracyGame()];

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

    public function deleteUser($user_id)
    {
        $user = User::find($user_id);
        $user->delete();
    }

    public function listAppUsers()
    {
        $users = AppUser::all();

        return View::make("users/app_users", ["users" => $users]);
    }

    public function newAppUser()
    {
        return View::make("users/new_appuser");
    }

    public function addAppUser()
    {
        $user           = new AppUser();
        $user->username = Input::get("username");
        $user->password = Input::get("password");
        $user->save();

        $this->generateHTPasswd();
    }

    public function removeAppUser($id)
    {
        $user = AppUser::find($id);
        $user->delete();

        $this->generateHTPasswd();
    }

    public function passwordAppUser($id, $password)
    {
        $user           = AppUser::find($id);
        $user->password = $password;
        $user->save();

        $this->generateHTPasswd();
    }

    private function generateHTPasswd()
    {
        $users = AppUser::all();
        $fp = fopen(base_path() . "/.htpasswd", "w") or die("Unable to open file!");

        ftruncate($fp, 0);

        foreach ($users as $user) {
            fwrite($fp, $user->username . ":" . $this->crypt_apr1_md5($user->password) . "\n");
        }

        fclose($fp);
    }

    private function crypt_apr1_md5($plainpasswd)
    {
        $tmp  = "";
        $salt = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz0123456789"), 0, 8);
        $len  = strlen($plainpasswd);
        $text = $plainpasswd . '$apr1$' . $salt;
        $bin  = pack("H32", md5($plainpasswd . $salt . $plainpasswd));
        for ($i = $len; $i > 0; $i -= 16) {
            $text .= substr($bin, 0, min(16, $i));
        }
        for ($i = $len; $i > 0; $i >>= 1) {
            $text .= ($i & 1) ? chr(0) : $plainpasswd{0};
        }
        $bin = pack("H32", md5($text));
        for ($i = 0; $i < 1000; $i++) {
            $new = ($i & 1) ? $plainpasswd : $bin;
            if ($i % 3)
                $new .= $salt;
            if ($i % 7)
                $new .= $plainpasswd;
            $new .= ($i & 1) ? $bin : $plainpasswd;
            $bin = pack("H32", md5($new));
        }
        for ($i = 0; $i < 5; $i++) {
            $k = $i + 6;
            $j = $i + 12;
            if ($j == 16)
                $j = 5;
            $tmp = $bin[$i] . $bin[$k] . $bin[$j] . $tmp;
        }
        $tmp = chr(0) . chr(0) . $bin[11] . $tmp;
        $tmp = strtr(strrev(substr(base64_encode($tmp), 2)), "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/", "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");

        return "$" . "apr1" . "$" . $salt . "$" . $tmp;
    }
}