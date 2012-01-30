<?php

namespace Hatimeria\MagentoApiBundle\Api;

use \Exception;

class ApiException extends Exception
{
    const UNKNOWN_ERROR = 0;

    const INTERNAL_ERROR = 1;

    const ACCESS_DENIED = 2;

    const INVALID_API_PATH = 3;

    const RESOURCE_PATH_NOT_CALLABLE = 4;

    const SESSION_EXPIRED = 5;

    public function getDetailsMessage()
    {
        if (null === $this->previous) {
            return '';
        }

        return $this->previous->getMessage();
    }

}