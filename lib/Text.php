<?php

    class Text {

        public static function spell($n, $s1, $s2, $s3) {
            $n = $n % 100;

            if($n > 9 && $n < 20) {
                return $s3;
            }

            $n = $n % 10;

            if($n > 1 && $n < 5) {
                return $s2;
            }

            if($n == 1) {
                return $s1;
            }

            return $s3;
        }
    }
