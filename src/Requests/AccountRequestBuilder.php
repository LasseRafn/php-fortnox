<?php

namespace LasseRafn\Fortnox\Requests;

use LasseRafn\Fortnox\Models\Voucher;
use LasseRafn\Fortnox\Utils\RequestBuilder;

class AccountRequestBuilder extends RequestBuilder
{
    /**
     * @param $guid
     *
     * @return \LasseRafn\Fortnox\Utils\Model|mixed|Voucher
     */
    public function find($guid)
    {
        return parent::find($guid);
    }
}
