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
        Mail::send('email_log', array(), function ($message) {
            $message->to(["mathew@icrm.net.au"])->subject("Ecers Log " . date("H:i:s d/m/Y"));
        });

        $entries = Input::get("entries");

        foreach ($entries as $entry) {
            $ecersEntry           = new EcersEntry();
            $ecersEntry->centre   = $entry["user_info"]["centre"];
            $ecersEntry->room     = $entry["user_info"]["room"];
            $ecersEntry->observer = $entry["user_info"]["observer"];
            $ecersEntry->study    = Input::get("study");
            $ecersEntry->start    = (!empty($entry["date"])) ? $entry["date"] . ":00" : date("Y-m-d H:i:s");
            $ecersEntry->end      = (!empty($entry["end"])) ? $entry["end"] . ":00" : date("Y-m-d H:i:s");
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
        $ecersModel = new Ecers;
        $entryData  = EcersData::where("entry_id", "=", $entry_id)
            ->orderBy("test", "ASC")
            ->orderBy("page", "ASC")
            ->orderBy("item", "ASC")
            ->orderBy("item_num", "ASC")->get();
        $pageData   = $ecersModel->getPageData();

        return View::make("ecers/scores", array(
            "entryData" => $entryData,
            "pageData"  => $pageData
        ));
    }

    public function makeCSV()
    {
        ini_set('max_execution_time', 300); // 5 minutes

        $ecersEntries = EcersEntry::all();
        $ecersModel   = new Ecers();
        $tests        = $ecersModel->getTests();
        $csvHeader    = $this->getCSVHeader();
        $csvData      = array();


        foreach ($ecersEntries as $ecersEntry) {
            $catScores = array();
            $csvRow    = array($ecersEntry->centre, $ecersEntry->room, $ecersEntry->start, $ecersEntry->end);

            // ************************************************
            // Get Average Score For Each Subscale (category)
            // - Score is the weighted score for each page (not the individual question answer)
            // ************************************************
            foreach ($tests as $test) {
                $catCount   = 1;
                $categories = $ecersModel->getCategoryData($test->test);

                foreach ($categories as $pages) {
                    $catScores[$catCount] = array(
                        "scores"  => array(),
                        "average" => 0
                    );

                    $entries = EcersData::whereIn("page", $pages)->orderBy("page")->orderBy("item")->orderBy("item_num")->get();

                    foreach ($entries as $entry) {
                        $catScores[$catCount]["scores"][$entry->page . ": " . $entry->item . "." . $entry->item_num] = $entry->value;
                    }

                    $catScores[$catCount]["average"] = array_sum($catScores[$catCount]["scores"]) / count($catScores[$catCount]["scores"]);
                    $csvRow[]                        = $catScores[$catCount]["average"];
                    $catCount++;
                }
            }

            // ************************************************
            // Get the special score (as per Steven Howard's algorithm)
            // ************************************************
            foreach ($tests as $test) {
                $testData = EcersData::where("test", "=", $test->test)->where("entry_id","=", $ecersEntry->id)->get();
                $pages    = $ecersModel->getPageDataForTest($test->test);

                foreach ($pages as $page => $page_name) {
                    $next    = false;
                    $entry   = $this->getEntryDataForPage($testData, $page);
                    $score   = 0;
                    $Level3  = $this->pageValueCount($entry, 3);
                    $L3Count = $Level3["count"];
                    $L3Yes   = $Level3["yes"];
                    $Level5  = $this->pageValueCount($entry, 5);
                    $L5Count = $Level5["count"];
                    $L5Yes   = $Level5["yes"];
                    $Level7  = $this->pageValueCount($entry, 7);
                    $L7Count = $Level7["count"];
                    $L7Yes   = $Level7["yes"];

                    //echo "TEST ID: " . $ecersEntry->id . ", Test(" . $test->test . ") Page:" . $page;

                    // ********************************************
                    // If any Level 1 items are YES then Score = 1
                    // ********************************************
                    foreach ($entry as $entryData) {
                        if ($entryData->item == 1 && $entryData->value == 0) {
                            $score = 1;
                            $next  = true;
                        }
                    }
                    // *************************************************************
                    // Else if less than 50% of Level 3 items are YES then Score = 1
                    // *************************************************************
                    if ($score == 0 && $next == false) {
                        if ($L3Yes < ($L3Count * 0.5)) {
                            $score = 1;
                            $next  = true;
                        }
                    }

                    // *************************************************************
                    // If => 50% but < 100% of level 3 items are YES then Score = 2
                    // *************************************************************
                    if ($next == false) {
                        if ($L3Yes >= ($L3Count * 0.5) && $L3Yes < $L3Count) {
                            $score = 2;
                            $next  = true;
                        } else if ($L3Yes < $L3Count) {
                            // If < 100% of level 3 items are YES then <END>
                            $next = true;
                        }
                    }

                    // ---------------------------------------
                    // OR If all level 3 items are yes, then…
                    // ---------------------------------------

                    // *************************************************************
                    // If less than 50% of level 5 items are yes (1), then score = 3
                    // If => 50% but < 100% of level 5 items are yes (1), then score = 4
                    // *************************************************************
                    if ($next == false) {
                        if ($L5Yes < ($L5Count * 0.5)) {
                            $score = 3;
                            $next  = true;
                        } else if ($L5Yes >= ($L5Count * 0.5) && $L5Yes < $L5Count) {
                            $score = 4;
                            $next  = true;
                        }
                    }

                    // ---------------------------------------
                    // OR If all level 5 items are yes, then…
                    // ---------------------------------------

                    // **************************************************************
                    // If less than 50% of level 7 items are YES, then score = 5
                    // If => 50% but < 100% of level 7 items are yes then score = 6
                    // **************************************************************
                    if ($next == false) {
                        if ($L7Yes < ($L7Count * 0.5)) {
                            $score = 5;
                        } else if ($L7Yes >= ($L7Count * 0.5) && $L7Yes < $L7Count) {
                            $score = 6;
                        } else if ($L7Yes == $L7Count) {
                            $score = 7;
                        }
                    }

                    $csvRow[] = $score;
                }
            }

            $csvData[] = $csvRow;
        }

        return View::make("ecers/test_csv", array(
            "header" => $csvHeader,
            "rows"   => $csvData
        ));
    }

    private function getCSVHeader()
    {
        $ecersModel = new Ecers();
        $tests      = $ecersModel->getTests();
        $csvHeader  = array(
            "Centre", "Room", "Date Start", "Date End"
        );

        foreach ($tests as $test) {
            $catCount   = 1;
            $categories = $ecersModel->getCategoryData($test->test);

            foreach ($categories as $category) {
                $csvHeader[] = $test->test . "_Subscale_" . $catCount;

                $catCount++;
            }
        }

        foreach ($tests as $test) {
            $pageData = $ecersModel->getPageDataForTest($test->test);

            foreach ($pageData as $page => $page_name) {
                $csvHeader[] = $test->test . "_" . $page;
            }

            foreach ($pageData as $page => $page_name) {
                //$csvHeader[] = $test->test . "_" . $page . "_Score";
            }
        }

        return $csvHeader;
    }

    private function getEntryDataForPage($data, $page)
    {
        $entryData = array();

        foreach ($data as $entry) {
            if ($entry->page == $page) {
                $entryData[] = $entry;
            }
        }

        return $entryData;
    }

    private function pageValueCount($pageData, $item)
    {
        $count = 0;
        $yes   = 0;

        foreach ($pageData as $entryData) {
            if ($entryData["item"] == $item) {
                $count++;
                if ($entryData["value"] == 0) {
                    $yes++;
                }
            }
        }

        return array(
            "count" => $count,
            "yes"   => $yes
        );
    }
}