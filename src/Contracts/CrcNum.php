<?php

namespace Chowhwei\Id\Contracts;

interface CrcNum
{
    public function getCrcId(int $id): int;

    public function getOrigId(int $crc_id): int;

    public function getMixedCrcId(int $id, int $id2): int;

    public function getMixedOrigId(int $crc_id): array;
}