<?php

namespace Chowhwei\Id;

use Exception;
use Chowhwei\Id\Contracts\CrcNum as CrcNumContract;

class CrcNum implements CrcNumContract
{
    protected $crc_key = '';

    /**
     * CrcId constructor.
     * @param string $crc_key
     * @throws Exception
     */
    public function __construct(string $crc_key = '')
    {
        $this->crc_key = $crc_key;
    }

    /**
     * @param int $id
     * @return int
     */
    public function getCrcId(int $id): int
    {
        return $id * 1000 + intval(crc32($id . $this->crc_key)) % 1000;
    }

    public function getOrigId($id): int
    {
        $ori_id = ($id - $id % 1000) / 1000;
        $new_id = $this->getCrcId($ori_id);
        if ($new_id != $id) {
            return 0;
        }

        return $ori_id;
    }

    public function getMixedCrcId(int $id1, int $id2): int
    {
        $id = 0;
        $i = 0;
        while ($id1 > 0 || $id2 > 0) {
            if ($id1 > 0) {
                $id += $id1 % 10 * pow(10, $i);
                $id1 = ($id1 - $id1 % 10) / 10;
            }
            $i++;
            if ($id2 > 0) {
                $id += $id2 % 10 * pow(10, $i);
                $id2 = ($id2 - $id2 % 10) / 10;
            }
            $i++;
        }

        return $this->getCrcId($id);
    }

    public function getMixedOrigId(int $id): array
    {
        $id = $this->getOrigId($id);
        if ($id == 0) {
            return null;
        }
        $id1 = 0;
        $id2 = 0;
        $i = 0;
        while ($id > 0) {
            $id1 += $id % 10 * pow(10, $i);
            $id = ($id - $id % 10) / 10;
            if ($id == 0) {
                break;
            }
            $id2 += $id % 10 * pow(10, $i);
            $id = ($id - $id % 10) / 10;
            $i++;
        }

        return [$id1, $id2];
    }
}