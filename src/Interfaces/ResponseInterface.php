<?php

namespace Adereksisusanto\DapodikAPI\Interfaces;

use Adereksisusanto\DapodikAPI\Collection;

interface ResponseInterface
{
    public function get(): Collection;
}
