<?php

namespace LasseRafn\Fortnox\Exceptions;

use GuzzleHttp\Exception\ClientException;

class FortnoxRequestException extends ClientException
{
    public $validationErrors = [];
    public $fortnoxCode;

    public function __construct(ClientException $clientException)
    {
        $message = $clientException->getMessage();

        if ($clientException->hasResponse()) {
            $messageResponse = json_decode($clientException->getResponse()
                                                            ->getBody()
                                                            ->getContents());
            $message = $clientException->getMessage();

            if (isset($messageResponse->ErrorInformation) && isset($messageResponse->ErrorInformation->Code)) {
            	$this->fortnoxCode = $messageResponse->ErrorInformation->Code;
                $message = "{$messageResponse->ErrorInformation->Code}: {$messageResponse->ErrorInformation->Message}";
            }

            if (isset($messageResponse->ErrorInformation) && isset($messageResponse->ErrorInformation->code)) {
	            $this->fortnoxCode = $messageResponse->ErrorInformation->code;
                $message = "{$messageResponse->ErrorInformation->code}: {$messageResponse->ErrorInformation->message}";
            }
        }

        $request = $clientException->getRequest();
        $response = $clientException->getResponse();
        $previous = $clientException->getPrevious();
        $handlerContext = $clientException->getHandlerContext();

        parent::__construct($message, $request, $response, $previous, $handlerContext);
    }
}
