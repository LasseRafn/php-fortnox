<?php

namespace LasseRafn\Fortnox\Requests;

use LasseRafn\Fortnox\Utils\RequestBuilder;

class InvoiceRequestBuilder extends RequestBuilder
{
	public function onlyCancelled()
	{
		$this->parameters['filter'] = 'cancelled';

		return $this;
	}

	public function onlyFullyPaid()
	{
		$this->parameters['filter'] = 'fullypaid';

		return $this;
	}

	public function onlyUnpaid()
	{
		$this->parameters['filter'] = 'unpaid';

		return $this;
	}

	public function onlyUnpaidOverdue()
	{
		$this->parameters['filter'] = 'unpaidoverdue';

		return $this;
	}

	public function onlyUnbooked()
	{
		$this->parameters['filter'] = 'unbooked';

		return $this;
	}
}
