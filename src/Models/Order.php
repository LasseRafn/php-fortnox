<?php

namespace LasseRafn\Fortnox\Models;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use LasseRafn\Fortnox\Exceptions\FortnoxRequestException;
use LasseRafn\Fortnox\Exceptions\FortnoxServerException;
use LasseRafn\Fortnox\Objects\EmailInformation;
use LasseRafn\Fortnox\Utils\Model;

class Order extends Model
{
	protected $entity          = 'orders';
	protected $entity_singular = 'Order';
	protected $primaryKey      = 'DocumentNumber';

	public $Cancelled;
	public $Currency;
	public $CustomerName;
	public $CustomerNumber;
	public $DeliveryDate;
	public $DocumentNumber;
	public $ExternalInvoiceReference1;
	public $ExternalInvoiceReference2;
	public $OrderDate;
	public $Project;
	public $Sent;
	public $Total;

	/**
	 * @return self
	 */
	public function createInvoice() // todo test
	{
		try {
			$response = $this->request->curl->put( "{$this->entity}/{$this->{$this->primaryKey}}/createinvoice" );

			$responseData = json_decode( $response->getBody()->getContents() );

			return new $this->modelClass( $this->request, $responseData->{$this->getSingularEntity()} );
		} catch ( ClientException $exception ) {
			throw new FortnoxRequestException( $exception );
		} catch ( ServerException $exception ) {
			throw new FortnoxServerException( $exception );
		}
	}

	/**
	 * @return self
	 */
	public function cancel()
	{
		try {
			$response = $this->request->curl->put( "{$this->entity}/{$this->{$this->primaryKey}}/cancel" );

			$responseData = json_decode( $response->getBody()->getContents() );

			return new $this->modelClass( $this->request, $responseData->{$this->getSingularEntity()} );
		} catch ( ClientException $exception ) {
			throw new FortnoxRequestException( $exception );
		} catch ( ServerException $exception ) {
			throw new FortnoxServerException( $exception );
		}
	}

	/**
	 * @param EmailInformation|null $emailInformation
	 *
	 * Specifying EmailInformation will update the invoice data.
	 *
	 * @return self
	 */
	public function email( $emailInformation = null )
	{
		if ( $emailInformation ) {
			$this->update( [ 'EmailInformation' => $emailInformation->toArray() ] );
		}

		try {
			$response = $this->request->curl->get( "{$this->entity}/{$this->{$this->primaryKey}}/email" );

			$responseData = json_decode( $response->getBody()->getContents() );

			return new $this->modelClass( $this->request, $responseData->{$this->getSingularEntity()} );
		} catch ( ClientException $exception ) {
			throw new FortnoxRequestException( $exception );
		} catch ( ServerException $exception ) {
			throw new FortnoxServerException( $exception );
		}
	}

	/**
	 * @return PDF string
	 */
	public function print()
	{
		try {
			$response = $this->request->curl->get( "{$this->entity}/{$this->{$this->primaryKey}}/print" );

			return $response->getBody()->getContents();
		} catch ( ClientException $exception ) {
			throw new FortnoxRequestException( $exception );
		} catch ( ServerException $exception ) {
			throw new FortnoxServerException( $exception );
		}
	}

	/**
	 * @return PDF string
	 */
	public function printReminder()
	{
		try {
			$response = $this->request->curl->get( "{$this->entity}/{$this->{$this->primaryKey}}/printreminder" );

			return $response->getBody()->getContents();
		} catch ( ClientException $exception ) {
			throw new FortnoxRequestException( $exception );
		} catch ( ServerException $exception ) {
			throw new FortnoxServerException( $exception );
		}
	}

	/**
	 * @return self
	 */
	public function externalPrint()
	{
		try {
			$response = $this->request->curl->put( "{$this->entity}/{$this->{$this->primaryKey}}/externalprint" );

			$responseData = json_decode( $response->getBody()->getContents() );

			return new $this->modelClass( $this->request, $responseData->{$this->getSingularEntity()} );
		} catch ( ClientException $exception ) {
			throw new FortnoxRequestException( $exception );
		} catch ( ServerException $exception ) {
			throw new FortnoxServerException( $exception );
		}
	}

	/**
	 * @return PDF string
	 */
	public function preview()
	{
		try {
			$response = $this->request->curl->get( "{$this->entity}/{$this->{$this->primaryKey}}/preview" );

			return $response->getBody()->getContents();
		} catch ( ClientException $exception ) {
			throw new FortnoxRequestException( $exception );
		} catch ( ServerException $exception ) {
			throw new FortnoxServerException( $exception );
		}
	}
}
