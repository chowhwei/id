<?php

namespace Chowhwei\Id;

use Illuminate\Config\Repository as Config;
use Exception;

class CrcId
{
    protected $crc_key = '';

    /**
     * CrcId constructor.
     * @param Config $config
     * @throws Exception
     */
    public function __construct(Config $config)
    {
        if(!$config->has('id.crc_key'))
        {
            throw new Exception('crc_key undefined');
        }

        $this->crc_key = $config->get('id.crc_key');
    }

    /**
     * 根据流水号获得带自校验的码
     * a a a c x r y d z CRC32取三位(crd)
     * @param int $id
     * @return int
     */
    function get_crc_id($id)
    {
        return $id * 1000 + intval(crc32($id . $this->crc_key)) % 1000;
    }

    /**
     * 根据自校验码获得原始编号，失败为0
     * @param int $id
     * @return int
     */
    function get_orig_id($id)
    {
        $ori_id = ($id - $id % 1000) / 1000;
        $new_id = $this->get_crc_id($ori_id);
        if ($new_id != $id) {
            return 0;
        }

        return $ori_id;
    }

    /**
     * 获得混合的自校验码
     * @param int $id1
     * @param int $id2
     * @return int
     */
    function get_mixed_crc_id($id1, $id2)
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

        return $this->get_crc_id($id);
    }

    /**
     * 根据混合的自校验码获得原始编号
     * @param int $id
     * @return int[]
     */
    function get_mixed_orig_id($id)
    {
        $id = $this->get_orig_id($id);
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