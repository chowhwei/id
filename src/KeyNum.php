<?php

namespace Chowhwei\Id;

use Chowhwei\Id\Contracts\KeyNum as KeyNumContract;

class KeyNum implements KeyNumContract
{
    public function getKey(int $id): string
    {
        $res = $id;
        $sKey = '';
        while ($res > 0) {
            $s = $res % 36;
            if ($s > 9) {
                $s = chr($s + (ord('a') - 10));
            } else {
                $s = chr($s + ord('0'));
            }
            $sKey = $s . $sKey;
            $res = floor($res / 36);
        }
        return $sKey;
    }

    public function getId(string $key): int
    {
        $vv = 0;
        for ($i = 0; $i < strlen($key); $i++) {
            if (ord($key[$i]) >= ord('a')) {
                $v = ord($key[$i]) - ord('a') + 10;
            } else {
                $v = ord($key[$i]) - ord('0');
            }
            $vv = $vv * 36 + $v;
        }
        return $vv;
    }
}