<?php

namespace App\Exceptions\_Base;

use Exception;
use App\Enums\ApiHttpStatus;

/**
 * Class BaseException
 */
abstract class BaseException extends Exception
{
	/**
	 * data
	 *
	 * @var
	 */
	private $data;

	/**
	 * BaseException constructor.
	 *
	 * @param array $inputs
	 */
	public function __construct( array $inputs = [] )
	{
        parent::__construct();

        $this->message = $inputs[ 'message' ] ?? $this->getMessageBase( $inputs[ 'params' ] ?? null );
        $this->code    = $inputs[ 'code' ] ?? $this->getCodeBase();
        $this->data    = $inputs[ 'data' ] ?? [];
    }

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
     * Return the extra data.
     *
     * @return array|mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * Return the message.
     *
     * @param $params
     * @return mixed
     */
    public function getMessageBase( $params = null ): mixed
    {
        if( !empty( $this->getMessage() ) )
        {
            return $this->getMessage();
        }

        return trans( 'exception._base.default' );
    }

    /**
     * Return code.
     *
     * @return mixed
     */
    public function getCodeBase(): mixed
    {
        if( !empty( $this->getCode() ) )
        {
            return $this->getCode();
        }

        return ApiHttpStatus::BAD_REQUEST;
    }
}
