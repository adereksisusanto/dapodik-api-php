<?php

namespace Adereksisusanto\DapodikAPI;

use Adereksisusanto\DapodikAPI\Connections\Connection;
use Adereksisusanto\DapodikAPI\Connections\WebServiceConnection;
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
}
