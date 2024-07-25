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

            foreach ($matches[1] as $i => $imageSource) {
                $remoteSource = $imageSource;

                if (substr($remoteSource, 0, 2) == '//') {
                    $remoteSource = str_replace('//', 'https://', $remoteSource);
                }

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
                    $imageListRow[] = $remoteSource;

                    $size += strlen($sourceResult);

                    $count++;
                }

                if (count($imageListRow) == self::$columnCount || $i + 1 == count($matches[1])) {
                    $imageList[] = $imageListRow;

                    $imageListRow = [];
                }
            }

            require 'views/list.php';
        }
    }
    