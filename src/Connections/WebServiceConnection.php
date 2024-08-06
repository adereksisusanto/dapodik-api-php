<?php

namespace Adereksisusanto\DapodikAPI\Connections;

use Adereksisusanto\DapodikAPI\Exceptions\DapodikException;
use Adereksisusanto\DapodikAPI\Interfaces\ResponseInterface;
use Adereksisusanto\DapodikAPI\Interfaces\WebServiceInterface;
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
     * @return ResponseInterface
     * @throws DapodikException
     */
    public function sekolah(): ResponseInterface
    {
        $uri = $this->getConfig('path').'/getSekolah';
        $response = $this->request('GET', $uri);

        return new Response($response);
    }

    /**
     * Get Pengguna
     *
     * @return ResponseInterface
     * @throws DapodikException
     */
    public function pengguna(): ResponseInterface
    {
        $uri = $this->getConfig('path').'/getPengguna';
        $response = $this->request('GET', $uri);

        return new Response($response);
    }

    /**
     * Get Rombongan Belajar
     *
     * @return ResponseInterface
     * @throws DapodikException
     */
    public function rombel(): ResponseInterface
    {
        $uri = $this->getConfig('path').'/getRombonganBelajar';
        $response = $this->request('GET', $uri);

        return new Response($response);
    }

    /**
     * Get Peserta Didik
     *
     * @return ResponseInterface
     * @throws DapodikException
     */
    public function pd(): ResponseInterface
    {
        $uri = $this->getConfig('path').'/getPesertaDidik';
        $response = $this->request('GET', $uri);

        return new Response($response);
    }

    /**
     * Get GTK (Guru dan Tendik)
     *
     * @return ResponseInterface
     * @throws DapodikException
     */
    public function gtk(): ResponseInterface
    {
        $uri = $this->getConfig('path').'/getGtk';
        $response = $this->request('GET', $uri);

        return new Response($response);
    }
}
