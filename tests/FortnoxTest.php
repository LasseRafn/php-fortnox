<?php

namespace LasseRafn\Fortnox\Tests;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use LasseRafn\Fortnox\Exceptions\FortnoxRequestException;
use LasseRafn\Fortnox\Exceptions\FortnoxServerException;
use LasseRafn\Fortnox\Fortnox;
use LasseRafn\Fortnox\Requests\ContactRequestBuilder;
use LasseRafn\Fortnox\Requests\CreditnoteRequestBuilder;
use LasseRafn\Fortnox\Requests\InvoiceRequestBuilder;
use LasseRafn\Fortnox\Requests\ProductRequestBuilder;

class FortnoxTest extends TestCase
{
    /** @test */
    public function can_authorize()
    {
        $fortnox = new Fortnox('7EbBznJgT2', '2c4a0a9d-5831-4a21-9237-90bed24ca5e1');

        var_dump($fortnox->invoices()->get());
        exit;

        $this->assertEquals(null, $fortnox->getAuthToken());
        $this->assertEquals(null, $fortnox->getOrgId());

        $fortnox->setAuth('foo', 'bar');

        $this->assertSame('foo', $fortnox->getAuthToken());
        $this->assertSame('bar', $fortnox->getOrgId());
    }

    /** @test */
    public function can_set_auth_info()
    {
        $fortnox = new Fortnox('clientId', 'clientSecret');

        $this->assertEquals(null, $fortnox->getAuthToken());
        $this->assertEquals(null, $fortnox->getOrgId());

        $fortnox->setAuth('foo', 'bar');

        $this->assertSame('foo', $fortnox->getAuthToken());
        $this->assertSame('bar', $fortnox->getOrgId());
    }

    /** @test */
    public function can_auth_with_the_api()
    {
        $expectedResponse = [
            'access_token'  => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9(...)',
            'token_type'    => 'Bearer',
            'expires_in'    => 3600,
            'refresh_token' => null,
        ];

        $mock = new MockHandler([
            new Response(200, [], json_encode($expectedResponse)),
        ]);

        $handler = HandlerStack::create($mock);

        $fortnox = new Fortnox('clientId', 'clientSecret', null, null, ['handler' => $handler]);

        $this->assertNull($fortnox->getOrgId());
        $this->assertNull($fortnox->getAuthToken());

        $authResponse = $fortnox->auth('foo', 'bar');

        $this->assertSame($expectedResponse['access_token'], $authResponse->access_token);
        $this->assertSame($expectedResponse['token_type'], $authResponse->token_type);
        $this->assertSame($expectedResponse['expires_in'], $authResponse->expires_in);
        $this->assertSame($expectedResponse['refresh_token'], $authResponse->refresh_token);

        $this->assertSame($expectedResponse['access_token'], $fortnox->getAuthToken());
        $this->assertSame('bar', $fortnox->getOrgId());
    }

    /** @test */
    public function can_return_builders()
    {
        $fortnox = new Fortnox('clientId', 'clientSecret');

        $this->assertSame(ContactRequestBuilder::class, get_class($fortnox->contacts()));
        $this->assertSame(InvoiceRequestBuilder::class, get_class($fortnox->invoices()));
        $this->assertSame(CreditnoteRequestBuilder::class, get_class($fortnox->creditnotes()));
        $this->assertSame(ProductRequestBuilder::class, get_class($fortnox->products()));
    }

    /** @test */
    public function can_get_auth_url()
    {
        $fortnox = new Fortnox('clientId', 'clientSecret');

        $this->assertSame('https://authz.dinero.dk/dineroapi/oauth/token', $fortnox->getAuthUrl());
    }

    /** @test */
    public function can_fail_to_auth_with_the_api_because_of_invalid_data()
    {
        $this->expectException(FortnoxRequestException::class);

        $expectedResponse = [
            'error' => 'invalid_client',
        ];

        $mock = new MockHandler([
            new Response(401, [], json_encode($expectedResponse)),
        ]);

        $handler = HandlerStack::create($mock);

        $fortnox = new Fortnox('clientId', 'clientSecret', null, null, ['handler' => $handler]);

        $fortnox->auth('foo', 'bar');
    }

    /** @test */
    public function can_fail_to_auth_with_the_api_because_of_server_problems()
    {
        $this->expectException(FortnoxServerException::class);

        $expectedResponse = [
            'error' => 'A server error occured. No more information about this error could be found.',
        ];

        $mock = new MockHandler([
            new Response(503, [], json_encode($expectedResponse)),
        ]);

        $handler = HandlerStack::create($mock);

        $fortnox = new Fortnox('clientId', 'clientSecret', null, null, ['handler' => $handler]);

        $fortnox->auth('foo', 'bar');
    }
}
