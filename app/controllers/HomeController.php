<?php

class HomeController extends BaseController
{
    public function homePage()
    {
        return View::make("layout");
    }

    public function makeCSV($test_name = null, $start = null, $end = null)
    {
        $csv_files = array();
        $games = array(
            new CardSortController(),
            new FishSharkController(),
            new MrAntController(),
            new NotThisController(),
            new VocabController()
        );

        foreach($games as $game) {
            $csv_files[] = $game->makeCSV($test_name, $start, $end, true);
        }

        var_dump($csv_files);
    }

    public function supportPage()
    {
        return View::make("support");
    }
}