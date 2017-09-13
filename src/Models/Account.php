<?php

namespace LasseRafn\Fortnox\Models;

use LasseRafn\Fortnox\Utils\Model;

class Account extends Model
{
    protected $entity = 'accounts';
    protected $entity_singular = 'Account';
    protected $primaryKey = 'Number';

    public $Active;
    public $Description;
    public $Number;
    public $SRU;
    public $Year;
}
