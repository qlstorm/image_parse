<?php

    class PageParser {

        private static $columnCount = 4;

        public static function start() {
            if ($_GET['url']) {
                self::parse($_GET['url']);

                return;
            }

            require 'views/form.php';
        }

        public static function parse($url) {
            $content = file_get_contents($url);

            $hostList = [];

            preg_match('/(.+\/\/.+?)\//', $url, $matches);

            if ($matches[1]) {
                $hostList[] = $matches[1];
            }

            preg_match('/(.+\/\/.+\/)/', $url, $matches);

            if ($matches[1]) {
                $hostList[] = $matches[1];
            }

            preg_match('/(.+?)\//', $url, $matches);

            if ($matches[1] && !in_array($matches[1], ['https:', 'http:', ''])) {
                $hostList[] = $matches[1];
            }

            preg_match('/(.+\/)/', $url, $matches);

            if ($matches[1] && !in_array($matches[1], ['https:/', 'http:/', ''])) {
                $hostList[] = $matches[1];
            }

            if (!$hostList) {
                $hostList[] = $url;
                $hostList[] = $url . '/';
            }

            $matches = [];

            preg_match_all('/img.*?src="(\S+)"/', $content, $matches);

            $imageList = [];

            $size = 0;
            $count = 0;

            $matches[1] = array_unique($matches[1]);

            $row = 0;
            $column = 0;

            foreach ($matches[1] as $imageSource) {
                $remoteSource = $imageSource;

                $sourceResult = file_get_contents($remoteSource);

                if (!$sourceResult) {
                    foreach ($hostList as $host) {
                        $remoteSource = $host . $imageSource;

                        $sourceResult = file_get_contents($remoteSource);

                        if ($sourceResult) {
                            break;
                        }
                    }
                    
                }

                if ($sourceResult) {
                    $imageList[$row][$column] = $remoteSource;

                    $size += strlen($sourceResult);

                    $count++;

                    $column++;

                    if ($column == self::$columnCount) {
                        $row++;
                        $column = 0;
                    }
                }
            }

            require 'views/list.php';
        }
    }
    