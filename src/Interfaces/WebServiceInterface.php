<?php

namespace Adereksisusanto\DapodikAPI\Interfaces;

use Adereksisusanto\DapodikAPI\Collection;

interface WebServiceInterface
{
    /**
     * Get Sekolah
     *
     * @return Collection
     */
    public function sekolah(): Collection;

    /**
     * Get Pengguna
     *
     * @return Collection
     */
    public function pengguna(): Collection;

    /**
     * Get Rombongan Belajar
     *
     * @return Collection
     */
    public function rombel(): Collection;

    /**
     * Get Peserta Didik
     *
     * @return Collection
     */
    public function pd(): Collection;

    /**
     * Get GTK (Guru dan Tendik)
     *
     * @return Collection
     */
    public function gtk(): Collection;
}
