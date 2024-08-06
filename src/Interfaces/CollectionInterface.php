<?php

namespace Adereksisusanto\DapodikAPI\Interfaces;

interface CollectionInterface
{
    /**
     * @param int $flags [optional] <p>
     * Bitmask consisting of <b>JSON_HEX_QUOT</b>,
     * <b>JSON_HEX_TAG</b>,
     * <b>JSON_HEX_AMP</b>,
     * <b>JSON_HEX_APOS</b>,
     * <b>JSON_NUMERIC_CHECK</b>,
     * <b>JSON_PRETTY_PRINT</b>,
     * <b>JSON_UNESCAPED_SLASHES</b>,
     * <b>JSON_FORCE_OBJECT</b>,
     * <b>JSON_UNESCAPED_UNICODE</b>.
     * <b>JSON_THROW_ON_ERROR</b> The behaviour of these
     * constants is described on
     * the JSON constants page.
     * </p>
     * @param int $depth [optional] <p>
     * Set the maximum depth. Must be greater than zero.
     * </p>
     * @return string|false a JSON encoded string on success or <b>FALSE</b> on failure.
     */
    public function toJson(int $flags = 0, int $depth = 512): string;
}
