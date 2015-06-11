<?php

class Ecers
{
    public function getPageData()
    {
        $data  = array();
        $tests = $this->getTests();

        foreach ($tests as $test) {
            $data[$test->test] = array();

            $pages = DB::connection('sqlite')->select("SELECT page, page_name FROM questions WHERE test = :test", array(":test" => $test->test));

            foreach ($pages as $page) {
                $data[$test->test][$page->page] = $page->page_name;
            }
        }

        return $data;
    }

    public function getPageDataForTest($test)
    {
        $pageData = array();
        $pages    = DB::connection('sqlite')->select("SELECT page, page_name FROM questions WHERE test = :test", array(":test" => $test));

        foreach ($pages as $page) {
            $pageData[$page->page] = $page->page_name;
        }

        return $pageData;
    }

    public function getCategoryData($test)
    {
        $categories = array();
        $pages      = DB::connection('sqlite')->select("SELECT page, category FROM questions WHERE test = :test GROUP BY page", array(":test" => $test));

        foreach ($pages as $page) {
            if (empty($categories[$page->category])) {
                $categories[$page->category] = array();
            }

            $categories[$page->category][] = $page->page;
        }

        return $categories;
    }

    public function getTests()
    {
        $tests = DB::connection('sqlite')->select("SELECT test FROM questions GROUP BY test");

        return $tests;
    }
} 