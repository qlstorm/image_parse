<?php

    class Rus {

        public static function spell($n, $s1, $s2, $s3) {
            if($n >= 11 and $n <= 19) {
                return $s3;
            }

            $n = $n % 10;

            if($n == 1) {
                return $s1;
            }

            if($n >= 2 and $n <= 4) {
                return $s2;
            }

            return $s3;
        }
    }
