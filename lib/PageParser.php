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

            $urlResult = parse_url($url);

            $hostList = [];

            $host = $urlResult['scheme'] . '://' . $urlResult['host'];

            $hostList[] = $host;
            $hostList[] = $host . '/';

            preg_match('/(.+\/)/', $urlResult['path'], $match);

            $hostList[] = $host . $match[1];

            preg_match_all('/img.*?src="(\S+)"/', $content, $matches);

            $imageList = [];

            $size = 0;
            $count = 0;

            $matches[1] = array_unique($matches[1]);

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
                    $imageListRow[] = $remoteSource;

                    $size += strlen($sourceResult);

                    $count++;

                    if (count($imageListRow) == self::$columnCount || $count == count($matches[1])) {
                        $imageList[] = $imageListRow;

                        $imageListRow = [];
                    }
                }
            }

            require 'views/list.php';
        }
    }
    