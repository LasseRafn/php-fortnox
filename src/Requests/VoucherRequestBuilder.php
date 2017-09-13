<?php

namespace LasseRafn\Fortnox\Requests;

use LasseRafn\Fortnox\Models\Invoice;
use LasseRafn\Fortnox\Models\Order;
use LasseRafn\Fortnox\Models\Voucher;
use LasseRafn\Fortnox\Utils\RequestBuilder;

class VoucherRequestBuilder extends RequestBuilder
{
	/**
	 * @param $series
	 *
	 * @return self
	 */
    public function inSeries( $series )
    {
    	$this->urlAdditions[] = 'sublist/' . $series;

	    return $this;
    }

	/**
	 * @param $guid
	 *
	 * @return \LasseRafn\Fortnox\Utils\Model|mixed|Voucher
	 */
    public function find( $guid )
    {
	    return parent::find( $guid );
    }

    public function findInSeries( $guid, $series )
    {
	    return $this->find( "{$series}/{$guid}" );
    }
}
