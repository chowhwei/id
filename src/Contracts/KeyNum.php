<?php

namespace Chowhwei\Id\Contracts;

interface KeyNum
{
    public function getKey(int $id): string;

    public function getId(string $key): int;
}