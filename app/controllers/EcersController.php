<?php

class EcersController extends BaseController
{
    public function showResults($test_name = null, $start = null, $end = null)
    {
        $entries   = EcersEntry::all();
        $tests     = App::make('perms');
        $testNames = array();

        foreach ($tests as $test) {
            $key = str_replace("+", "%20", urlencode($test->test_name));
            if (!isset($testNames[$key])) {
                $testNames[$key] = $test;
            }
        }

        return View::make("ecers/results", array(
            "entries"   => $entries,
            "test_name" => $test_name,
            "start"     => (!empty($start)) ? DateTime::createFromFormat("Y-m-d", $start)->format("d/m/Y") : null,
            "end"       => (!empty($end)) ? DateTime::createFromFormat("Y-m-d", $end)->format("d/m/Y") : null,
            "tests"     => $testNames
        ));
    }

    public function saveEntries()
    {
        // Log game data
        Mail::send('email_log', array(), function($message) {
            $message->to(["mathew@icrm.net.au"])->subject("Ecers Log " . date("H:i:s d/m/Y"));
        });

        $entries = Input::get("entries");

        foreach ($entries as $entry) {
            $ecersEntry           = new EcersEntry();
            $ecersEntry->centre   = $entry["user_info"]["centre"];
            $ecersEntry->room     = $entry["user_info"]["room"];
            $ecersEntry->observer = $entry["user_info"]["observer"];
            $ecersEntry->study    = Input::get("study");
            $ecersEntry->save();

            $dataEntries = array();

            foreach ($entry["saved_data"] as $test => $pages) {
                foreach ($pages as $page_no => $pageData) {
                    foreach ($pageData as $itemData => $value) {
                        $item     = explode(".", $itemData)[0];
                        $item_num = explode(".", $itemData)[1];

                        $dataEntries[] = array(
                            "entry_id" => $ecersEntry->id,
                            "test"     => $test,
                            "page"     => $page_no,
                            "item"     => $item,
                            "item_num" => $item_num,
                            "value"    => $value
                        );
                    }
                }
            }

            foreach ($dataEntries as $entry) {
                $ecersData           = new EcersData();
                $ecersData->entry_id = $entry["entry_id"];
                $ecersData->test     = $entry["test"];
                $ecersData->page     = $entry["page"];
                $ecersData->item     = $entry["item"];
                $ecersData->item_num = $entry["item_num"];
                $ecersData->value    = $entry["value"];
                $ecersData->save();
            }
        }

        return ["result" => "success"];
    }

    public function viewEntry($entry_id)
    {
        $entryData = EcersData::where("entry_id", "=", $entry_id)->orderBy("test", "ASC")->orderBy("page", "ASC")->orderBy("item", "ASC")->orderBy("item_num", "ASC")->get();

        return View::make("ecers/scores", array(
            "entryData" => $entryData
        ));
    }
}