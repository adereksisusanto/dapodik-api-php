<?php

namespace Adereksisusanto\DapodikAPI\Connections;

use Adereksisusanto\DapodikAPI\Collections\Collection;
use Adereksisusanto\DapodikAPI\Contracts\WebServiceInterface;
use Adereksisusanto\DapodikAPI\Exceptions\DapodikException;
use Adereksisusanto\DapodikAPI\Response;

class WebServiceConnection extends Connection implements WebServiceInterface
{
    public function __construct(array $config, array $auth)
    {
        parent::__construct($config);
        $this->setConfig('path', '/WebService');
        $this->setHeaders("Authorization", "Bearer {$auth['token']}");
        $this->setQuery("npsn", $auth['npsn']);
    }

    /**
     * Get Sekolah
     *
     * @return Collection
     * @throws DapodikException
     */
    public function sekolah(): Collection
    {
        $uri = $this->getConfig('path').'/getSekolah';
        $response = $this->request('GET', $uri);

        return new Response($response);
    }

    /**
     * Get Pengguna
     *
     * @return Collection
     * @throws DapodikException
     */
    public function pengguna(): Collection
    {
        $uri = $this->getConfig('path').'/getPengguna';
        $response = $this->request('GET', $uri);

        return new Response($response);
    }

    /**
     * Get Rombongan Belajar
     *
     * @return Collection
     * @throws DapodikException
     */
    public function rombel(): Collection
    {
        $uri = $this->getConfig('path').'/getRombonganBelajar';
        $response = $this->request('GET', $uri);

        return new Response($response);
    }

    /**
     * Get Peserta Didik
     *
     * @return Collection
     * @throws DapodikException
     */
    public function pd(): Collection
    {
        $uri = $this->getConfig('path').'/getPesertaDidik';
        $response = $this->request('GET', $uri);

        return new Response($response);
    }

    /**
     * Get GTK (Guru dan Tendik)
     *
     * @return Collection
     * @throws DapodikException
     */
    public function gtk(): Collection
    {
        $uri = $this->getConfig('path').'/getGtk';
        $response = $this->request('GET', $uri);

        return new Response($response);
    }
}
