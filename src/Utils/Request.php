<?php

namespace LasseRafn\Fortnox\Utils;

use GuzzleHttp\Client;

class Request
{
	public $curl;

	protected $baseUri = 'https://api.fortnox.se/3/';

	public function __construct( $clientSecret = '', $accessToken = '', $clientConfig = [] )
	{
		$headers                 = [];
		$headers['Content-Type'] = 'application/json';
		$headers['Accept']       = 'application/json';
		$headers['Client-Secret'] = $clientSecret;

		if ( $accessToken !== '' && $accessToken !== null ) {
			$headers['Access-Token'] = $accessToken;
		}

		$this->curl = new Client( array_merge_recursive( [
			'base_uri' => $this->baseUri,
			'headers'  => $headers,
		], $clientConfig ) );
	}
}
