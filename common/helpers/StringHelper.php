<?php

namespace common\helpers;

class StringHelper extends \yii\helpers\StringHelper
{
    public static function getRandomString(
        $len = 6,
        $include_capital_characters = true,
        $include_special_characters = false
    ) {
        $chars = [
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k',
            'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v',
            'w', 'x', 'y', 'z', '0', '1', '2',
            '3', '4', '5', '6', '7', '8', '9',
        ];
        if ($include_capital_characters) {
            $chars = array_merge($chars, [
                'A', 'B', 'C', 'D', 'E', 'F', 'G',
                'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R',
                'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            ]);
        }
        if ($include_special_characters) {
            $chars = array_merge($chars, [
                '~', '`', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '+', '_',
                '=', '<', '>', '?', '{', '}', '[', ']', ':', ';', ',', '.',
            ]);
        }
        $charsLen = count($chars) - 1;
        shuffle($chars); // 将数组打乱

        $output = '';
        for ($i = 0; $i < $len; ++$i) {
            $output .= $chars[mt_rand(0, $charsLen)];
        }

        return $output;
    }
}
