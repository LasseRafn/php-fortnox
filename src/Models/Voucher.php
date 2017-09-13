<?php

namespace LasseRafn\Fortnox\Models;

use LasseRafn\Fortnox\Utils\Model;

class Voucher extends Model
{
	protected $entity          = 'vouchers';
	protected $entity_singular = 'Voucher';
	protected $primaryKey      = 'ReferenceNumber';

	public $Comments;
	public $Description;
	public $ReferenceNumber;
	public $ReferenceType;
	public $VoucherNumber;
	public $VoucherSeries;
	public $Year;
	public $ApprovalState;
}
