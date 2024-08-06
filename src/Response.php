<?php

namespace Adereksisusanto\DapodikAPI;

use Adereksisusanto\DapodikAPI\Exceptions\DapodikException;
use Adereksisusanto\DapodikAPI\Interfaces\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponse;
use Psr\Http\Message\StreamInterface;

class Response implements ResponseInterface
{
    protected string $reason;
    protected int $code;
    protected array $headers;
    protected StreamInterface $body;
    protected string $content;

    /**
     * @param PsrResponse $response
     * @throws DapodikException
     */
    public function __construct(PsrResponse $response)
    {
        $this->reason = $response->getReasonPhrase();
        $this->code = $response->getStatusCode();
        $this->headers = $response->getHeaders();
        $this->body = $response->getBody();
        $content = $response->getBody()->getContents();
        if (! is_null($error = preg_match("/\{.*?['\"]success['\"]:.*?false,(.*?)}/", $content, $match) ? $match[0] : null)) {
            throw new DapodikException(json_decode($error)->message, json_decode($error)->http_code);
        }
        $this->content = $content;
    }

    public function get(): Collection
    {
        return new Collection(json_decode($this->content, true)['rows']);
    }
}
