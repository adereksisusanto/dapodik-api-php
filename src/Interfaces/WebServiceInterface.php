<?php

namespace Adereksisusanto\DapodikAPI\Interfaces;

interface WebServiceInterface
{
    /**
     * Get Sekolah
     *
     * @return ResponseInterface
     */
    public function sekolah(): ResponseInterface;

    /**
     * Get Pengguna
     *
     * @return ResponseInterface
     */
    public function pengguna(): ResponseInterface;

    /**
     * Get Rombongan Belajar
     *
     * @return ResponseInterface
     */
    public function rombel(): ResponseInterface;

    /**
     * Get Peserta Didik
     *
     * @return ResponseInterface
     */
    public function pd(): ResponseInterface;

    /**
     * Get GTK (Guru dan Tendik)
     *
     * @return ResponseInterface
     */
    public function gtk(): ResponseInterface;
}
