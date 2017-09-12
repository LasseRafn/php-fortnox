<?php

namespace LasseRafn\Fortnox\Responses;

use GuzzleHttp\Psr7\Response;

class PaginatedResponse
{
    /** @var array */
    public $items;

    public $page;
    public $totalPages;
    public $totalResults;

    public function __construct(Response $response, $collectionName = 'Collection')
    {
        $jsonResponse = json_decode($response->getBody()->getContents());

        $this->items = $jsonResponse->{$collectionName};

        $this->page = $jsonResponse->MetaInformation->{'@CurrentPage'};
        $this->totalPages = $jsonResponse->MetaInformation->{'@TotalPages'};
        $this->totalResults = $jsonResponse->MetaInformation->{'@TotalResources'};
    }

    public function setItems(array $items)
    {
        $this->items = $items;

        return $this;
    }
}
