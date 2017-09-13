<?php

namespace LasseRafn\Fortnox\Utils\Traits;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use LasseRafn\Fortnox\Exceptions\FortnoxRequestException;
use LasseRafn\Fortnox\Exceptions\FortnoxServerException;
use Psr\Http\Message\ResponseInterface;

trait Deleteable
{
	/**
	 * Send a request to the API to delete the model.
	 *
	 * @return ResponseInterface
	 */
	public function delete()
	{
		try {
			return $this->request->curl->delete( "{$this->entity}/{$this->{$this->primaryKey}}" );
		} catch ( ClientException $exception ) {
			throw new FortnoxRequestException( $exception );
		} catch ( ServerException $exception ) {
			throw new FortnoxServerException( $exception );
		}
	}
}