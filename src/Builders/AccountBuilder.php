<?php

namespace LasseRafn\Fortnox\Builders;

use LasseRafn\Fortnox\Models\Account;

class AccountBuilder extends Builder
{
    protected $entity = 'Accounts';
    protected $entity_singular = 'Account';
    protected $model = Account::class;
}
