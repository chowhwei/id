<?php

namespace Chowhwei\Id;

class KeyId
{
    protected $keyNum;

    public function __construct()
    {
        $this->keyNum = new KeyNum();
    }

    public function get_key($id)
    {
        return $this->keyNum->getKey($id);
    }

    public function get_id($key)
    {
        return $this->keyNum->getId($key);
    }
}