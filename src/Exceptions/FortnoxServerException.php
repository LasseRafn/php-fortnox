<?php

namespace LasseRafn\Fortnox\Exceptions;

use GuzzleHttp\Exception\ServerException;

class FortnoxServerException extends ServerException
{
    public function __construct(ServerException $serverException)
    {
        $message = $serverException->getMessage();

        if ($serverException->hasResponse()) {
            $messageResponse = json_decode($serverException->getResponse()
                                                            ->getBody()
                                                            ->getContents());

            $message = $serverException->getMessage();

            if (isset($messageResponse->ErrorInformation) && isset($messageResponse->ErrorInformation->Code)) {
                $message = "{$messageResponse->ErrorInformation->Code}: {$messageResponse->ErrorInformation->Message}";
            }

            if (isset($messageResponse->ErrorInformation) && isset($messageResponse->ErrorInformation->code)) {
                $message = "{$messageResponse->ErrorInformation->code}: {$messageResponse->ErrorInformation->message}";
            }
        }

        $request = $serverException->getRequest();
        $response = $serverException->getResponse();
        $previous = $serverException->getPrevious();
        $handlerContext = $serverException->getHandlerContext();

        parent::__construct($message, $request, $response, $previous, $handlerContext);
    }
}
