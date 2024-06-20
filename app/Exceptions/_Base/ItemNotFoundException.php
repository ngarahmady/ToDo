<?php

namespace App\Exceptions\_Base;

use App\Enums\ApiHttpStatus;

class ItemNotFoundException extends BaseException
{
    /**
     * Should send to sentry or not.
     *
     * @return boolean
     */
    public function shouldSendToSentry(): bool
    {
        return true;
    }

    /**
     * Return the message.
     *
     * @param $params
     * @return mixed
     */
    public function getMessageBase( $params = null ): mixed
    {
        return trans( 'exception._base.not_found.item.default' );
    }

    /**
     * Return code.
     *
     * @return int
     */
    public function getCodeBase(): int
    {
        return ApiHttpStatus::NOT_FOUND;
    }
}
