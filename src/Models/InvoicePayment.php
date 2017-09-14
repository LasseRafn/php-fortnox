<?php

namespace LasseRafn\Fortnox\Models;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use LasseRafn\Fortnox\Exceptions\FortnoxRequestException;
use LasseRafn\Fortnox\Exceptions\FortnoxServerException;
use LasseRafn\Fortnox\Utils\Model;

class InvoicePayment extends Model
{
	protected $entity          = 'invoicepayments';
	protected $entity_singular = 'InvoicePayment';
	protected $primaryKey      = 'Number';

	public $Amount;
	public $AmountCurrency;
	public $Booked;
	public $Currency;
	public $CurrencyRate;
	public $CurrencyUnit;
	public $ExternalInvoiceReference1;
	public $ExternalInvoiceReference2;
	public $InvoiceCustomerName;
	public $InvoiceCustomerNumber;
	public $InvoiceNumber;
	public $InvoiceDueDate;
	public $InvoiceOCR;
	public $InvoiceTotal;
	public $ModeOfPayment;
	public $Number;
	public $PaymentDate;
	public $VoucherNumber;
	public $VoucherSeries;
	public $VoucherYear;
	public $Source;
	public $WriteOffs;

	/**
	 * @return self
	 */
	public function bookkeep()
	{
		try {
			$response = $this->request->curl->put( "{$this->entity}/{$this->{$this->primaryKey}}/bookkeep" );

			$responseData = json_decode( $response->getBody()->getContents() );

			return new $this->modelClass( $this->request, $responseData->{$this->getSingularEntity()} );
		} catch ( ClientException $exception ) {
			throw new FortnoxRequestException( $exception );
		} catch ( ServerException $exception ) {
			throw new FortnoxServerException( $exception );
		}
	}
}
