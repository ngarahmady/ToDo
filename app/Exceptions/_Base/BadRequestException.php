<?php

namespace App\Exceptions\_Base;

use App\Enums\ApiHttpStatus;

class BadRequestException extends BaseException
{
    /**
     * Should send to sentry or not.
     *
     * @return boolean
     */
    public function shouldSendToSentry(): bool
    {
        return false;
    }

    /**
     * Return the message.
     *
     * @param $params
     * @return mixed
     */
    public function getMessageBase( $params = null ): mixed
    {
        return trans( 'exception._base.bad_request' );
    }

    /**
     * Return code.
     *
     * @return int
     */
    public function getCodeBase(): int
    {
        return ApiHttpStatus::BAD_REQUEST;
    }
}
