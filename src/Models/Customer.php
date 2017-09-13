<?php

namespace LasseRafn\Fortnox\Models;

use LasseRafn\Fortnox\Utils\Model;
use LasseRafn\Fortnox\Utils\Traits\Deleteable;

class Customer extends Model
{
	use Deleteable;

	protected $entity          = 'customers';
	protected $entity_singular = 'Customer';
	protected $primaryKey      = 'CustomerNumber';

	public $Address1;
	public $Address2;
	public $City;
	public $CustomerNumber;
	public $Email;
	public $Name;
	public $OrganisationNumber;
	public $Phone;
	public $ZipCode;
}
