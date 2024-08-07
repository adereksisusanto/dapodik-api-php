<?php

namespace Adereksisusanto\DapodikAPI;

use Adereksisusanto\DapodikAPI\Exceptions\DapodikException;
use Psr\Http\Message\ResponseInterface as PsrResponse;

class Response extends Collection
{
    /**
     * @param PsrResponse $response
     * @throws DapodikException
     */
    public function __construct(PsrResponse $response)
    {
        $content = $response->getBody()->getContents();
        if (! is_null($error = preg_match("/\{.*?['\"]success['\"]:.*?false,(.*?)}/", $content, $match) ? $match[0] : null)) {
            throw new DapodikException(json_decode($error)->message, json_decode($error)->http_code);
        }
        parent::__construct(json_decode($content, true)['rows']);
    }
}
