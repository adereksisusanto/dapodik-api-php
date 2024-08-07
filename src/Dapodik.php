<?php

namespace Adereksisusanto\DapodikAPI;

use Adereksisusanto\DapodikAPI\Connections\Connection;
use Adereksisusanto\DapodikAPI\Connections\RestConnection;
use Adereksisusanto\DapodikAPI\Connections\WebServiceConnection;
use Adereksisusanto\DapodikAPI\Interfaces\RestInterface;
use Adereksisusanto\DapodikAPI\Interfaces\WebServiceInterface;

class Dapodik
{
    protected Connection $connection;

    /**
     * @param string|null $host
     * @param string|null $port
     * @throws Exceptions\DapodikException
     */
    public function __construct(string $host = null, string $port = null)
    {
        $config = [
            'host' => $host ?: Connection::HOST,
            'port' => $port ?: Connection::PORT,
        ];
        $this->connection = new Connection($config);
    }

    /**
     * @param string $token
     * @param string $npsn
     * @return WebServiceInterface
     * @throws Exceptions\DapodikException
     */
    public function api(string $token, string $npsn): WebServiceInterface
    {
        $auth = [
            'token' => $token,
            'npsn' => $npsn,
        ];

        return new WebServiceConnection($this->connection->getConfig(), $auth);
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $kode_registrasi
     * @return RestInterface
     * @throws Exceptions\DapodikException
     */
    public function login(string $username, string $password, string $kode_registrasi): RestInterface
    {
        $auth = [
            'username' => $username,
            'password' => $password,
            'kode_registrasi' => $kode_registrasi,
        ];

        return new RestConnection($this->connection->getConfig(), $auth);
    }
}
