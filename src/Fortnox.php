<?php

namespace LasseRafn\Fortnox;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use LasseRafn\Fortnox\Builders\AccountBuilder;
use LasseRafn\Fortnox\Builders\CustomerBuilder;
use LasseRafn\Fortnox\Builders\InvoiceBuilder;
use LasseRafn\Fortnox\Builders\OrderBuilder;
use LasseRafn\Fortnox\Builders\VoucherBuilder;
use LasseRafn\Fortnox\Exceptions\FortnoxRequestException;
use LasseRafn\Fortnox\Exceptions\FortnoxServerException;
use LasseRafn\Fortnox\Requests\AccountRequestBuilder;
use LasseRafn\Fortnox\Requests\CustomerRequestBuilder;
use LasseRafn\Fortnox\Requests\InvoiceRequestBuilder;
use LasseRafn\Fortnox\Requests\OrderRequestBuilder;
use LasseRafn\Fortnox\Requests\VoucherRequestBuilder;
use LasseRafn\Fortnox\Utils\Request;

class Fortnox
{
    protected $request;

    private $accessToken;
    private $clientSecret;
    private $clientConfig;

    public function __construct($clientSecret = '', $accessToken = '', $clientConfig = [])
    {
        $this->accessToken = $accessToken;
        $this->clientSecret = $clientSecret;
        $this->clientConfig = $clientConfig;

        $this->request = new Request($this->clientSecret, $this->accessToken, $this->clientConfig);
    }

    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        $this->request = new Request($this->clientSecret, $this->accessToken, $this->clientConfig);
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function authorize($authorizationCode)
    {
        try {
            $this->clientConfig['headers'] = $this->clientConfig['headers'] ?? [];
            $this->clientConfig['headers']['Authorization-Code'] = $authorizationCode;

            $this->request = new Request($this->clientSecret, $this->accessToken, $this->clientConfig);

            $response = json_decode($this->request->curl->get('')->getBody()->getContents());

            if (!isset($response->Authorization)) {
                throw new \Exception('Not authorized.');
            }

            unset($this->clientConfig['headers']['Authorization-Code']);

            $this->setAccessToken($response->Authorization->AccessToken);

            return $this;
        } catch (ClientException $exception) {
            throw new FortnoxRequestException($exception);
        } catch (ServerException $exception) {
            throw new FortnoxServerException($exception);
        }
    }

    public function customers()
    {
        return new CustomerRequestBuilder(new CustomerBuilder($this->request));
    }

    public function invoices()
    {
        return new InvoiceRequestBuilder(new InvoiceBuilder($this->request));
    }

    public function orders()
    {
        return new OrderRequestBuilder(new OrderBuilder($this->request));
    }

    public function vouchers()
    {
        return new VoucherRequestBuilder(new VoucherBuilder($this->request));
    }

    public function accounts()
    {
        return new AccountRequestBuilder(new AccountBuilder($this->request));
    }
}
