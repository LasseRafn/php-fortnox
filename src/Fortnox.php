<?php

namespace LasseRafn\Fortnox;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use LasseRafn\Fortnox\Builders\ContactBuilder;
use LasseRafn\Fortnox\Builders\CreditnoteBuilder;
use LasseRafn\Fortnox\Builders\InvoiceBuilder;
use LasseRafn\Fortnox\Builders\ProductBuilder;
use LasseRafn\Fortnox\Exceptions\FortnoxRequestException;
use LasseRafn\Fortnox\Exceptions\FortnoxServerException;
use LasseRafn\Fortnox\Requests\ContactRequestBuilder;
use LasseRafn\Fortnox\Requests\CreditnoteRequestBuilder;
use LasseRafn\Fortnox\Requests\InvoiceRequestBuilder;
use LasseRafn\Fortnox\Requests\ProductRequestBuilder;
use LasseRafn\Fortnox\Utils\Request;

class Fortnox
{
	protected $request;

	private $accessToken;
	private $clientSecret;
	private $clientConfig;

	public function __construct( $clientSecret = '', $accessToken = '', $clientConfig = [] )
	{
		$this->accessToken  = $accessToken;
		$this->clientSecret = $clientSecret;
		$this->clientConfig = $clientConfig;

		$this->request = new Request( $this->clientSecret, $this->accessToken, $this->clientConfig );
	}

	public function setAccessToken( $accessToken )
	{
		$this->accessToken = $accessToken;

		$this->request = new Request( $this->clientSecret, $this->accessToken, $this->clientConfig );
	}

	public function getAccessToken()
	{
		return $this->accessToken;
	}

	public function authorize( $authorizationCode )
	{
		try {
			$this->clientConfig['headers']                       = $this->clientConfig['headers'] ?? [];
			$this->clientConfig['headers']['Authorization-Code'] = $authorizationCode;

			$this->request = new Request( $this->clientSecret, $this->accessToken, $this->clientConfig );

			$response = json_decode( $this->request->curl->get( '' )->getBody()->getContents() );

			if ( ! isset( $response->Authorization ) ) {
				throw new \Exception( 'Not authorized.' );
			}

			unset($this->clientConfig['headers']['Authorization-Code']);

			$this->setAccessToken( $response->Authorization->AccessToken );

			return $this;
		} catch ( ClientException $exception ) {
			throw new FortnoxRequestException( $exception );
		} catch ( ServerException $exception ) {
			throw new FortnoxServerException( $exception );
		}
	}

	public function contacts()
	{
		return new ContactRequestBuilder( new ContactBuilder( $this->request ) );
	}

	public function invoices()
	{
		return new InvoiceRequestBuilder( new InvoiceBuilder( $this->request ) );
	}

	public function products()
	{
		return new ProductRequestBuilder( new ProductBuilder( $this->request ) );
	}

	public function creditnotes()
	{
		return new CreditnoteRequestBuilder( new CreditnoteBuilder( $this->request ) );
	}
}
