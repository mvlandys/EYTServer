<?php

class BaseController extends Controller
{

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }

    public function viewGameData($type, $game_id)
    {
        $model = $this->getGameModel($type);

        return View::make("game_edit", ["type" => $type,
                                        "game" => $model->whereId($game_id)->first()]);
    }

    public function updateGameData()
    {
        $type     = Input::get("type");
        $game_id  = Input::get("game_id");
        $oldModel = $this->getGameModel($type)->whereId($game_id)->first();
        $newModel = $this->getGameModel($type)->whereId($game_id)->first();
        $viewData = ["old_data" => $oldModel];

        $newModel->subject_id = (Input::has("child_id")) ? Input::get("child_id") : $newModel->subject_id;
        $newModel->session_id = (Input::has("session_id")) ? Input::get("session_id") : $newModel->session_id;
        $newModel->test_name  = (Input::has("test_name")) ? Input::get("test_name") : $newModel->test_name;
        $newModel->grade      = (Input::has("grade")) ? Input::get("grade") : $newModel->grade;
        $newModel->dob        = (Input::has("dob")) ? Input::get("dob") : $newModel->dob;
        $newModel->age        = (Input::has("age")) ? Input::get("age") : $newModel->age;
        $newModel->sex        = (Input::has("sex")) ? Input::get("sex") : $newModel->sex;
        $newModel->save();

        $viewData["new_data"] = $newModel;

        Mail::send('change_log', $viewData, function($message) use ($type) {
            $message->to(["stevenh@uow.edu.au"])->subject($type . " Change Log " . date("H:i:s d/m/Y"));
        });

        return View::make("alert", ["type" => "success",
                                    "msg"  => "Successfully update game data"]);
    }

    private function getGameModel($type)
    {
        if ($type == "vocab") {
            return new VocabGameNew();
        }
    }
}
