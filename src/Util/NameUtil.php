<?php
namespace App\Util;

class NameUtil {
    // Normalize: trim, collapse whitespace, single spaces
    public static function normalize($name) {
        $name = trim($name);
        $name = preg_replace('/\s+/', ' ', $name);
        return $name;
    }
}
