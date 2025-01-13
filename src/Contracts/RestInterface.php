<?php

namespace Adereksisusanto\DapodikAPI\Contracts;

use Adereksisusanto\DapodikAPI\Collections\Collection;

interface RestInterface
{
    /**
     * Get Sekolah
     *
     * @param array $query
     * @return Collection
     */
    public function sekolah(array $query = []): Collection;

    /**
     * Get Peserta Didik
     *
     * @param array $query
     * @return Collection
     */
    public function pd(array $query = []): Collection;
}
