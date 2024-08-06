<?php

namespace Adereksisusanto\DapodikAPI\Connections;

use Adereksisusanto\DapodikAPI\Exceptions\DapodikException;
use Adereksisusanto\DapodikAPI\Helpers\Arr;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

class Connection
{
    public const HOST = '127.0.0.1';
    public const PORT = '5774';

    public const CONFIG_ALLOWED = [
        'host' => 'string',
        'port' => 'string',
        'path' => 'string',
    ];
    protected Client $client;
    protected CookieJar $cookie;
    protected array $options = [];
    protected array $config = [
        'host' => self::HOST,
        'port' => self::PORT,
        'path' => '',
    ];

    /**
     * @param array $config
     * @throws DapodikException
     */
    public function __construct(array $config = [])
    {
        foreach ($config as $key => $value) {
            $this->setConfig($key, $value);
        }
        $this->cookie = new CookieJar();
        $this->client = $this->setClient($config);
        if (! $this->isConnect()) {
            throw new DapodikException("Tidak terhubung dengan Dapodik, Silahkan cek host & port");
        }
    }

    /**
     * @param array $config
     * @return Client
     * @throws DapodikException
     */
    public function setClient(array $config): Client
    {
        $baseUri = $this->parseBaseUri($config);

        return new Client([
            'base_uri' => $baseUri,
            'cookies' => $this->cookie,
            'headers' => [
                "User-Agent" => "Adereksisusanto/DapodikAPI",
            ],
        ]);
    }

    /**
     * @param array $config
     * @return string
     * @throws DapodikException
     */
    public function parseBaseUri(array $config): string
    {
        if (! isset($config['host']) || ! isset($config['port'])) {
            throw new DapodikException("Parameter 'host' or 'port' not set.");
        }

        return Arr::join(array_merge([$config['host']], [$config['port']]), ':');
    }

    public function setHeaders(string $key, $value)
    {
        $this->options['headers'][$key] = $value;
    }

    public function getHeaders(string $key = null)
    {
        if (! is_null($key)) {
            return $this->options['headers'][$key];
        }

        return $this->options['headers'];
    }

    public function setQuery(string $key, $value)
    {
        $this->options['query'][$key] = $value;
    }

    public function getQuery(string $key = null)
    {
        if (! is_null($key)) {
            return $this->options['query'][$key];
        }

        return $this->options['query'];
    }

    /**
     * @param string $key
     * @param string $value
     * @return void
     * @throws DapodikException
     */
    public function setConfig(string $key, string $value)
    {
        if (! array_key_exists($key, self::CONFIG_ALLOWED)) {
            throw new DapodikException("Requested parameter '$key' not found in list [" . implode(', ', array_keys(self::CONFIG_ALLOWED)) . ']');
        }
        $this->config[$key] = $value;
    }

    public function getConfig(string $key = null)
    {
        if (! is_null($key)) {
            return $this->config[$key];
        }

        return $this->config;
    }

    /**
     * @param string $method
     * @param UriInterface|string $uri
     * @param array $options
     * @return ResponseInterface
     * @throws DapodikException
     */
    public function request(string $method, $uri, array $options = []): ResponseInterface
    {
        $options = array_merge($options, $this->options);

        try {
            return $this->client->request($method, $uri, $options);
        } catch (ConnectException|GuzzleException $e) {
            throw new DapodikException($e->getMessage(), $e->getCode());
        }
    }

    protected function loginPage(): ?string
    {
        try {
            $page = $this->request('GET', '/')->getBody()->getContents();
        } catch (DapodikException $e) {
            $page = null;
        }

        return $page;
    }

    public function isConnect(): bool
    {
        return ! is_null($this->loginPage());
    }
}
