<?php

namespace Log2Test;


use Symfony\Component\Yaml\Yaml;

trait Utils
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
            array('http://', 'www.'), 
            '', 
            $url
        );

        /**
          * http://php.net/manual/en/language.oop5.basic.php#language.oop5.basic.class
          * A valid class name starts with a letter or underscore, followed by any number of letters, numbers, or underscores
          *
          * To replace all characters not match : can use a negated character class (using ^ at the beginning of the class)
          */

        $cleanedString = preg_replace('/[^a-zA-Z_\x7f-\xff][^a-zA-Z0-9_\x7f-\xff]*/', '', $cleanedString, -1);
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
        foreach ($values as $value) {
            if (is_array($value)) {
                $value = $value[Constants::HOST_SOURCE];
            }
            if (true === $addSpaces) {
                if (stripos($str, Constants::SPACE_CHAR . $value . Constants::SPACE_CHAR . Constants::METHOD_GET) !== false) {

                    return $value;
                }
            } elseif (stripos($str, $value) !== false) {

                return $value;
            }
        }

        return null;
    }

    /**
     * Save the given content to a yaml file
     *
     * @param $file
     * @param $content
     */
    public static function saveYamlContent($file, $content)
    {
        $yaml = Yaml::dump($content);
        file_put_contents($file, $yaml);
    }

    /**
     * Create a Directory
     *
     * @param string $path
     * @param bool   $info
     *
     * @return bool
     */
    public static function createDir($path, $info = false)
    {
        if (!file_exists($path)) {
            $createdDir = mkdir($path);
            if (true === $createdDir && $info === true) {
                print '[INFO] Created directory: "' . $path . '"' . PHP_EOL;
            }

            return $createdDir;
        }
    }
}
