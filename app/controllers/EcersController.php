<?php

class EcersController extends BaseController
{
    public function showResults($test_name = null, $start = null, $end = null)
    {
        $gameRep   = new Games(new EcersEntry());
        $entries   = $gameRep->getEcersEntries($test_name, $start, $end);
        $tests     = App::make('perms');
        $testNames = [];

        foreach ($tests as $test) {
            $key = str_replace("+", "%20", urlencode($test->test_name));
            if (!isset($testNames[$key])) {
                $testNames[$key] = $test;
            }
        }

        return View::make("ecers/results", ["entries"   => $entries,
                                            "test_name" => $test_name,
                                            "start"     => (!empty($start)) ? DateTime::createFromFormat("Y-m-d", $start)->format("d/m/Y") : null,
                                            "end"       => (!empty($end)) ? DateTime::createFromFormat("Y-m-d", $end)->format("d/m/Y") : null,
                                            "tests"     => $testNames]);
    }

    public function saveEntries()
    {
        // Log game data
        Mail::send('email_log', [], function($message) {
            $message->to(["mathew@icrm.net.au"])->subject("Ecers Log " . date("H:i:s d/m/Y"));
        });

        $entries = Input::get("entries");

        foreach ($entries as $entry) {
            $ecersEntry           = new EcersEntry();
            $ecersEntry->centre   = $entry["user_info"]["centre"];
            $ecersEntry->room     = $entry["user_info"]["room"];
            $ecersEntry->observer = $entry["user_info"]["observer"];
            $ecersEntry->study    = (Input::has("study")) ? Input::get("study") : "data-upload";
            $ecersEntry->start    = (!empty($entry["date"])) ? $entry["date"] . ":00" : date("Y-m-d H:i:s");
            $ecersEntry->end      = (!empty($entry["end"])) ? $entry["end"] . ":00" : date("Y-m-d H:i:s");
            $ecersEntry->save();

            if (!empty($entry["app_notes"])) {
                foreach ($entry["app_notes"] as $note) {
                    $appNote          = new EcersAppNote();
                    $appNote->game_id = $ecersEntry->id;
                    $appNote->note    = $note;
                    $appNote->save();
                }
            }

            if (!empty($entry["notes"])) {
                foreach ($entry["notes"] as $note) {
                    $ecersNote          = new EcersNote();
                    $ecersNote->game_id = $ecersEntry->id;
                    $ecersNote->test    = $note["test"];
                    $ecersNote->page    = $note["page"];
                    $ecersNote->note    = $note["note"];
                    $ecersNote->save();
                }
            }

            if (!empty($entry["q_answers"])) {
                foreach ($entry["q_answers"] as $qKey => $question) {
                    $itemStudy = explode(" ", $qKey);

                    $ecersQuestion          = new EcersQuestion();
                    $ecersQuestion->game_id = $ecersEntry->id;
                    $ecersQuestion->study   = (empty($itemStudy[0])) ? $itemStudy[0] : $qKey;
                    $ecersQuestion->item    = (empty($itemStudy[0])) ? $itemStudy[1] : $qKey;
                    $ecersQuestion->answer  = $question;
                    $ecersQuestion->save();
                }
            }

            $dataEntries = [];

            if (empty($entry["saved_data"])) {
                echo "<pre>";
                echo "Skipping " . $entry["id"];
                //print_r($entry);
                echo "</pre>";
                continue;
            }

            foreach ($entry["saved_data"] as $test => $pages) {
                foreach ($pages as $page_no => $pageData) {
                    foreach ($pageData as $itemData => $value) {
                        if ($itemData == "page_na") {
                            continue;
                        }

                        $item     = explode(".", $itemData)[0];
                        $item_num = explode(".", $itemData)[1];

                        /*
                        if ($test == "ECERS-E") {
                            if ($page_no == 10) {
                                $page_no = 9.1;
                            }
                        }
                        */

                        $dataEntries[] = ["entry_id" => $ecersEntry->id,
                                          "test"     => $test,
                                          "page"     => $page_no,
                                          "item"     => $item,
                                          "item_num" => $item_num,
                                          "value"    => $value];
                    }
                }
            }

            foreach ($dataEntries as $dataEntry) {
                $ecersData           = new EcersData();
                $ecersData->entry_id = $dataEntry["entry_id"];
                $ecersData->test     = $dataEntry["test"];
                $ecersData->page     = $dataEntry["page"];
                $ecersData->item     = $dataEntry["item"];
                $ecersData->item_num = $dataEntry["item_num"];
                $ecersData->value    = $dataEntry["value"];
                $ecersData->save();
            }

            if (Input::has("email") && (!empty($entry["notes"]) || !empty($entry["q_answers"]) || !empty($entry["app_notes"]))) {
                // Log game data
                Log::info("Ecers Email");
                Mail::send('ecers_email', ["notes"     => $entry["notes"],
                                           "questions" => EcersQuestion::whereGameId($ecersEntry->id)->get(),
                                           "app_notes" => EcersAppNote::whereGameId($ecersEntry->id)->get()
                ], function($message) use ($ecersEntry) {
                    $message->to([Input::get("email")])->subject("Ecers Notes " . $ecersEntry->study . ": " . $ecersEntry->centre);
                });
            } else {
                Log::info("Email = " . Input::get("email"));
                Log::info("Notes = " . count($entry["notes"]));
                Log::info("Questions = " . count($entry["q_answers"]));
            }
        }

        return ["result" => "success"];
    }

    public function viewEntry($entry_id)
    {
        $ecersModel = new Ecers();
        $entryData  = EcersData::where("entry_id", "=", $entry_id)->orderBy("test", "ASC")->orderBy("page", "ASC")->orderBy("item", "ASC")->orderBy("item_num", "ASC")->get();
        $pageData   = $ecersModel->getPageData();

        $scaleScores = [];
        $scores      = [];
        $totals      = [];

        foreach ($entryData as $data) {
            if (!key_exists($pageData[$data->test][$data->page], $scores)) {


                $ecersEntryPageData = EcersData::whereEntryId($entry_id)->whereTest($data->test)->wherePage($data->page)->orderBy("item", "ASC")->orderBy("item_num", "ASC")->get();

                $scores[$pageData[$data->test][$data->page]] = ["score" => $this->getPageScore($ecersEntryPageData),
                                                                "data"  => []];
            }

            $scores[$pageData[$data->test][$data->page]]["data"][] = $data;

        }

        $ecersEntry = EcersEntry::whereId($entry_id)->first();

        return View::make("ecers/scores", ["totals"    => $totals,
                                           "scores"    => $scores,
                                           "entryData" => $entryData,
                                           "pageData"  => $pageData,
                                           "questions" => EcersQuestion::whereGameId($entry_id)->get(),
                                           "appNotes"  => EcersAppNote::where("game_id", "=", $entry_id)->get(),
                                           "notes"     => EcersNote::where("game_id", "=", $entry_id)->get(),
                                           "subscales" => $this->getSubscaleScores($ecersEntry)]);
    }

    public function makeCSV($test_name = null, $start = null, $end = null)
    {
        ini_set('max_execution_time', 300); // 5 minutes

        $gameRep      = new Games(new EcersEntry());
        $ecersEntries = $gameRep->getEcersEntries($test_name, $start, $end);
        $ecersModel   = new Ecers();
        $tests        = $ecersModel->getTests();
        $csvHeader    = $this->getCSVHeader();
        $csvData      = [];

        foreach ($ecersEntries as $ecersEntry) {
            $catScores = [];
            $csvRow    = [$ecersEntry->centre,
                          $ecersEntry->room,
                          $ecersEntry->start,
                          $ecersEntry->end];

            // ************************************************
            // Get Average Score For Each Subscale (category)
            // - Score is the weighted score for each page (not the individual question answer)
            // ************************************************
            foreach ($tests as $test) {
                $catCount   = 1;
                $categories = $ecersModel->getCategoryData($test->test);

                // Categories = groups of pages with page numbers (E.g. LITERACY(1, 2, 3, 5, 6), MATHEMATICS(7, 8, 9) etc)
                foreach ($categories as $pages) {
                    $catScores[$catCount] = ["scores"  => [],
                                             "average" => 0];

                    $entries  = EcersData::where("entry_id", "=", $ecersEntry->id)->where("test", "=", $test->test)->whereIn("page", $pages)->orderBy("page")->orderBy("item")->orderBy("item_num")->get();
                    $pageData = [];

                    // Get the results for the page and page-numbers
                    foreach ($entries as $entry) {

                        if (empty($pageData[$entry->page])) {
                            $pageData[$entry->page] = [];
                        }

                        $pageData[$entry->page][] = $entry;
                    }

                    foreach ($pageData as $page => $pageEntries) {
                        $score = $this->getPageScore($pageEntries);

                        if ($score != 0) {
                            $catScores[$catCount]["scores"][$page] = $this->getPageScore($pageEntries);
                        }
                    }

                    if ($test->test == "ECERS-E" && in_array("10", $pages)) {
                        $a = (empty($catScores[$catCount]["scores"][9])) ? 0 : $catScores[$catCount]["scores"][9];
                        $b = (empty($catScores[$catCount]["scores"][10])) ? 0 : $catScores[$catCount]["scores"][10];

                        if ($a > $b) {
                            unset($catScores[$catCount]["scores"][10]);
                        } else {
                            unset($catScores[$catCount]["scores"][9]);
                        }
                    }

                    if ($test->test == "ECERS-E" && in_array("12", $pages)) {

                        if (empty($catScores[$catCount]["scores"][15])) {
                            $catScores[$catCount]["scores"][15] = 0;
                        }

                        if (empty($catScores[$catCount]["scores"][13])) {
                            $catScores[$catCount]["scores"][13] = 0;
                        }

                        if (empty($catScores[$catCount]["scores"][14])) {
                            $catScores[$catCount]["scores"][14] = 0;
                        }

                        $a = $catScores[$catCount]["scores"][13];
                        $b = $catScores[$catCount]["scores"][14];
                        $c = $catScores[$catCount]["scores"][15];

                        if ($a > $b && $a > $c) {
                            unset($catScores[$catCount]["scores"][14]);
                            unset($catScores[$catCount]["scores"][15]);
                        } elseif ($b > $a && $b > $c) {
                            unset($catScores[$catCount]["scores"][13]);
                            unset($catScores[$catCount]["scores"][15]);
                        } elseif ($c > $a && $c > $b) {
                            unset($catScores[$catCount]["scores"][13]);
                            unset($catScores[$catCount]["scores"][14]);
                        } else {
                            unset($catScores[$catCount]["scores"][14]);
                            unset($catScores[$catCount]["scores"][15]);
                        }
                    }


                    if (count($entries) == 0) {
                        $catScores[$catCount]["average"] = ".";
                    } elseif (count($catScores[$catCount]["scores"]) == 0) {
                        $catScores[$catCount]["average"] = 0;
                    } else {

                        $catScores[$catCount]["average"] = array_sum($catScores[$catCount]["scores"]) / count($catScores[$catCount]["scores"]);
                    }


                    $csvRow[] = $catScores[$catCount]["average"];
                    $catCount++;
                }
            }

            // ************************************************
            // Get the special score (as per Steven Howard's algorithm)
            // ************************************************
            foreach ($tests as $test) {
                $testData = EcersData::where("test", "=", $test->test)->where("entry_id", "=", $ecersEntry->id)->get();
                $pages    = $ecersModel->getPageDataForTest($test->test);

                foreach ($pages as $page => $page_name) {

                    /*
                    if ($test->test == "Ecers-E") {
                        if ($page == 10) {
                            $page = 9.1;
                        }
                    }
                    */


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

                    // ********************************************
                    // Count Whole Page N/A as a full-stop
                    // ********************************************
                    $count   = count($entry);
                    $naCount = 0;

                    foreach ($entry as $entryData) {
                        $count++;
                        if ($entryData["value"] == 2 || $entryData["value"] == 9) {
                            $naCount++;
                        }
                    }

                    if ($count == $naCount) {
                        $score = ".";
                        $next  = true;
                    }

                    // ********************************************
                    // If any Level 1 items are YES then Score = 1
                    // ********************************************
                    if ($next == false) {
                        foreach ($entry as $entryData) {
                            if ($entryData->item == 1 && $entryData->value == 0) {
                                $score = 1;
                                $next  = true;
                            }
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

        $filename = "ecers__" . date("U") . ".csv";
        $fp       = fopen(public_path() . "/tmp/" . $filename, 'w');


        fputcsv($fp, $csvHeader);

        foreach ($csvData as $data) {
            fputcsv($fp, $data);
        }

        return View::make("csv", ["filename" => $filename]);

        return View::make("ecers/test_csv", ["header" => $csvHeader,
                                             "rows"   => $csvData]);
    }

    private function getPageScore($pageData)
    {
        $next    = false;
        $score   = 0;
        $Level3  = $this->pageValueCount($pageData, 3);
        $L3Count = $Level3["count"];
        $L3Yes   = $Level3["yes"];
        $Level5  = $this->pageValueCount($pageData, 5);
        $L5Count = $Level5["count"];
        $L5Yes   = $Level5["yes"];
        $Level7  = $this->pageValueCount($pageData, 7);
        $L7Count = $Level7["count"];
        $L7Yes   = $Level7["yes"];


        // ********************************************
        // Count Whole Page N/A as a full-stop
        // ********************************************
        $count   = count($pageData);
        $naCount = 0;

        foreach ($pageData as $entryData) {
            $count++;
            if ($entryData["value"] == 2 || $entryData["value"] == 9) {
                $naCount++;
            }
        }

        if ($count == $naCount) {
            return 0;
        }

        // ********************************************
        // If any Level 1 items are YES then Score = 1
        // ********************************************
        foreach ($pageData as $entryData) {
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

        return $score;
    }

    private function getCSVHeader()
    {
        $ecersModel = new Ecers();
        $tests      = $ecersModel->getTests();
        $csvHeader  = ["Centre",
                       "Room",
                       "Date Start",
                       "Date End"];

        foreach ($tests as $test) {
            $catCount   = 1;
            $categories = $ecersModel->getCategoryData($test->test);

            foreach ($categories as $category) {
                $testName    = str_replace("-", "", $test->test);
                $csvHeader[] = $testName . "_Subscale_" . $catCount;

                $catCount++;
            }
        }

        foreach ($tests as $test) {
            $pageData = $ecersModel->getPageDataForTest($test->test);

            foreach ($pageData as $page => $page_name) {
                if ($test->test == "ECERS-E" && $page == 9) {
                    $page = "9a";
                }
                if ($test->test == "ECERS-E" && $page == 10) {
                    $page = "9b";
                }
                if ($test->test == "ECERS-E" && $page == 11) {
                    $page = "10";
                }
                if ($test->test == "ECERS-E" && $page == 12) {
                    $page = "11";
                }
                if ($test->test == "ECERS-E" && $page == 13) {
                    $page = "12a";
                }
                if ($test->test == "ECERS-E" && $page == 14) {
                    $page = "12b";
                }
                if ($test->test == "ECERS-E" && $page == 15) {
                    $page = "12c";
                }
                if ($test->test == "ECERS-E" && $page > 15) {
                    $page -= 3;
                }

                $testName = str_replace("-", "", $test->test);

                $csvHeader[] = $testName . "_" . $page;
            }

            foreach ($pageData as $page => $page_name) {
                //$csvHeader[] = $test->test . "_" . $page . "_Score";
            }
        }

        return $csvHeader;
    }

    private function getEntryDataForPage($data, $page)
    {
        $entryData = [];

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
                if ($entryData["value"] == 2) {
                    continue;
                }

                $count++;
                if ($entryData["value"] == 0) {
                    $yes++;
                }
            }
        }

        return ["count" => $count,
                "yes"   => $yes];
    }

    public function fixDuplicates()
    {
        ini_set('max_execution_time', 300); // 5 minutes

        $games   = EcersEntry::all();
        $deleted = [];

        foreach ($games as $game) {
            if (in_array($game->id, $deleted)) {
                continue;
            }

            $duplicates = EcersEntry::where("id", "!=", $game->id)->where("centre", "=", $game->centre)->where("room", "=", $game->room)->where("observer", "=", $game->observer)->where("study", "=", $game->study)->where("start", "=", $game->start)->where("end", "=", $game->end)->get();

            foreach ($duplicates as $duplicate) {
                EcersData::where("entry_id", "=", $duplicate->id)->delete();
                EcersEntry::where("id", "=", $duplicate->id)->delete();

                $deleted[] = $duplicate->id;

                $info = ["center"   => $game->centre,
                         "room"     => $game->room,
                         "observer" => $game->observer];

                echo "<pre>";
                print_r($info);
                echo "</pre>";
            }
        }

        echo "Removed " . count($deleted) . " duplicates";
        exit();
    }

    public function deleteEntry($entry_id)
    {
        EcersData::where("entry_id", "=", $entry_id)->delete();
        EcersEntry::where("id", "=", $entry_id)->delete();

        return ["success" => true];
    }

    public function getSubscaleScores($ecersEntry)
    {
        $ecersModel = new Ecers();
        $tests      = $ecersModel->getTests();
        $csvRow     = [];

        // ************************************************
        // Get Average Score For Each Subscale (category)
        // - Score is the weighted score for each page (not the individual question answer)
        // ************************************************
        foreach ($tests as $test) {
            $catCount   = 1;
            $categories = $ecersModel->getCategoryData($test->test);

            // Categories = groups of pages with page numbers (E.g. LITERACY(1, 2, 3, 5, 6), MATHEMATICS(7, 8, 9) etc)
            foreach ($categories as $pages) {
                $catScores[$catCount] = ["scores"  => [],
                                         "average" => 0];

                $entries  = EcersData::where("entry_id", "=", $ecersEntry->id)->where("test", "=", $test->test)->whereIn("page", $pages)->orderBy("page")->orderBy("item")->orderBy("item_num")->get();
                $pageData = [];

                // Get the results for the page and page-numbers
                foreach ($entries as $entry) {

                    if (empty($pageData[$entry->page])) {
                        $pageData[$entry->page] = [];
                    }

                    $pageData[$entry->page][] = $entry;
                }

                foreach ($pageData as $page => $pageEntries) {
                    $score = $this->getPageScore($pageEntries);

                    if ($score != 0) {
                        $catScores[$catCount]["scores"][$page] = $this->getPageScore($pageEntries);
                    }
                }

                if ($test->test == "ECERS-E" && in_array("10", $pages)) {
                    $a = (empty($catScores[$catCount]["scores"][9])) ? 0 : $catScores[$catCount]["scores"][9];
                    $b = (empty($catScores[$catCount]["scores"][10])) ? 0 : $catScores[$catCount]["scores"][10];

                    if ($a > $b) {
                        unset($catScores[$catCount]["scores"][10]);
                    } else {
                        unset($catScores[$catCount]["scores"][9]);
                    }
                }

                if ($test->test == "ECERS-E" && in_array("12", $pages)) {

                    if (empty($catScores[$catCount]["scores"][15])) {
                        $catScores[$catCount]["scores"][15] = 0;
                    }

                    if (empty($catScores[$catCount]["scores"][13])) {
                        $catScores[$catCount]["scores"][13] = 0;
                    }

                    if (empty($catScores[$catCount]["scores"][14])) {
                        $catScores[$catCount]["scores"][14] = 0;
                    }

                    $a = $catScores[$catCount]["scores"][13];
                    $b = $catScores[$catCount]["scores"][14];
                    $c = $catScores[$catCount]["scores"][15];

                    if ($a > $b && $a > $c) {
                        unset($catScores[$catCount]["scores"][14]);
                        unset($catScores[$catCount]["scores"][15]);
                    } elseif ($b > $a && $b > $c) {
                        unset($catScores[$catCount]["scores"][13]);
                        unset($catScores[$catCount]["scores"][15]);
                    } elseif ($c > $a && $c > $b) {
                        unset($catScores[$catCount]["scores"][13]);
                        unset($catScores[$catCount]["scores"][14]);
                    } else {
                        unset($catScores[$catCount]["scores"][14]);
                        unset($catScores[$catCount]["scores"][15]);
                    }
                }


                if (count($entries) == 0) {
                    $catScores[$catCount]["average"] = ".";
                } elseif (count($catScores[$catCount]["scores"]) == 0) {
                    $catScores[$catCount]["average"] = 0;
                } else {

                    $catScores[$catCount]["average"] = array_sum($catScores[$catCount]["scores"]) / count($catScores[$catCount]["scores"]);
                }


                $csvRow[$test->test . " " . $catCount] = $catScores[$catCount]["average"];
                $catCount++;
            }
        }

        return $csvRow;
    }

    public function deleteGames()
    {
        $games   = Input::get("game_ids");
        $gameRep = new Games(new EcersEntry());

        return $gameRep->deleteGames(new EcersData(), $games);
    }
}