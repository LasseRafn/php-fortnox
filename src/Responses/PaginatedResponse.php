<?php

namespace LasseRafn\Fortnox\Responses;

use GuzzleHttp\Psr7\Response;

class PaginatedResponse implements \ArrayAccess
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

	public function offsetSet($offset, $value) {
		if ($offset === null) {
			$this->items[] = $value;
		} else {
			$this->items[$offset] = $value;
		}
	}

	public function offsetExists($offset) {
		return isset($this->items[$offset]);
	}

	public function offsetUnset($offset) {
		unset($this->items[$offset]);
	}

	public function offsetGet($offset) {
		return $this->items[$offset] ?? null;
	}
}
