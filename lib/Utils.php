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


    /**
     * Check if a string contains one string of our values array
     * Will then return the found value
     *
     * @param $str
     * @param array $values
     *
     * @return string|null
     */
    public static function contains($str, array $values, $addSpaces = false)
    {
        foreach($values as $value) {
            if (true === $addSpaces) {
                if (stripos($str, Constants::SPACE_CHAR . $value . Constants::SPACE_CHAR) !== false) {

                    return $value;
                }
            } else
            if (stripos($str, $value) !== false) {

                return $value;
            }
        }

        return null;
    }
}
