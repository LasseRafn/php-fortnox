<?php

namespace LasseRafn\Fortnox\Models;

use LasseRafn\Fortnox\Utils\Model;

class Invoice extends Model
{
    protected $entity = 'invoices';
    protected $primaryKey = 'DocumentNumber';

    public $Balance;
    public $Booked;
    public $Cancelled;
    public $Currency;
    public $CurrencyRate;
    public $CurrencyUnit;
    public $CustomerName;
    public $CustomerNumber;
    public $DocumentNumber;
    public $DueDate;
    public $ExternalInvoiceReference1;
    public $ExternalInvoiceReference2;
    public $InvoiceDate;
    public $NoxFinans;
    public $OCR;
    public $WayOfDelivery;
    public $TermsOfPayment;
    public $Project;
    public $Sent;
    public $Total;
}
