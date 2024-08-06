<?php

namespace Adereksisusanto\DapodikAPI\Helpers;

class Arr
{
    /**
     * Join all items using a string. The final items can use a separate glue string.
     *
     * @param array $array
     * @param string $glue
     * @param string $finalGlue
     * @return string
     */
    public static function join(array $array, string $glue, string $finalGlue = ''): string
    {
        if ($finalGlue === '') {
            return implode($glue, $array);
        }

        if (count($array) === 0) {
            return '';
        }

        if (count($array) === 1) {
            return end($array);
        }

        $finalItem = array_pop($array);

        return implode($glue, $array).$finalGlue.$finalItem;
    }
}
