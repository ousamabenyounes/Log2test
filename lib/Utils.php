<?php

namespace Log2Test;


class Utils
{
    /**
     * This function convert an url to a cleaned String (without http:// www. ...)
     *
     * @param string $url
     * @return string $cleanedString
     */
    public static function urlToString($url)
    {
        $cleanedString = str_replace(
            array(' ', 'http://', 'www.'),
            array('-', '', ''),
            $url
        );
        $cleanedString = preg_replace('/[^A-Za-z0-9\-]/', '', $cleanedString);
        return $cleanedString;
    }

}
