<?php

namespace Chowhwei\Id;

use Illuminate\Config\Repository as Config;
use Exception;

class CrcId
{
    protected $crcNum;

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

        $this->crcNum = new CrcNum($config->get('id.crc_key'));
    }

    /**
     * 根据流水号获得带自校验的码
     * a a a c x r y d z CRC32取三位(crd)
     * @param int $id
     * @return int
     */
    function get_crc_id($id)
    {
        return $this->crcNum->getCrcId($id);
    }

    /**
     * 根据自校验码获得原始编号，失败为0
     * @param int $id
     * @return int
     */
    function get_orig_id($id)
    {
        return $this->crcNum->getOrigId($id);
    }

    /**
     * 获得混合的自校验码
     * @param int $id1
     * @param int $id2
     * @return int
     */
    function get_mixed_crc_id($id1, $id2)
    {
        return $this->crcNum->getMixedCrcId($id1, $id2);
    }

    /**
     * 根据混合的自校验码获得原始编号
     * @param int $id
     * @return int[]
     */
    function get_mixed_orig_id($id)
    {
        return $this->crcNum->getMixedOrigId($id);
    }
}