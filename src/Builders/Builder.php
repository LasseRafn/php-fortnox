<?php

namespace LasseRafn\Fortnox\Builders;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use LasseRafn\Fortnox\Exceptions\FortnoxRequestException;
use LasseRafn\Fortnox\Exceptions\FortnoxServerException;
use LasseRafn\Fortnox\Responses\PaginatedResponse;
use LasseRafn\Fortnox\Utils\Model;
use LasseRafn\Fortnox\Utils\Request;

class Builder
{
    private $request;
    protected $entity;
    protected $entity_singular;

    /** @var Model */
    protected $model;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param $id
     *
     * @return mixed|Model
     */
    public function find($id)
    {
        try {
            $response = $this->request->curl->get("{$this->getUrlEntity()}/{$id}");
            $responseData = json_decode($response->getBody()->getContents());

            return new $this->model($this->request, $responseData->{$this->getSingularEntity()});
        } catch (ClientException $exception) {
            throw new FortnoxRequestException($exception);
        } catch (ServerException $exception) {
            throw new FortnoxServerException($exception);
        }
    }

    /**
     * @param string $parameters
     * @param string $urlAdditions
     *
     * @return PaginatedResponse
     */
    public function get($parameters = '', $urlAdditions = '')
    {
        try {
            $response = $this->request->curl->get("{$this->getUrlEntity()}{$urlAdditions}{$parameters}");
            $paginatedResponse = new PaginatedResponse($response, $this->getEntity());
        } catch (ClientException $exception) {
            throw new FortnoxRequestException($exception);
        } catch (ServerException $exception) {
            throw new FortnoxServerException($exception);
        }

        $request = $this->request;

        $paginatedResponse->setItems(array_map(function ($item) use ($request) {
            return new $this->model($request, $item);
        }, $paginatedResponse->items));

        return $paginatedResponse;
    }

    /**
     * Creates a model from a data array.
     * Sends a API request.
     *
     * @param array $data
     *
     * @throws FortnoxRequestException
     * @throws FortnoxServerException
     *
     * @return Model
     */
    public function create($data = [])
    {
        try {
            $response = $this->request->curl->post("{$this->getUrlEntity()}", [
                'json' => $data,
            ]);

            $responseData = (array) json_decode($response->getBody()->getContents());

            return new $this->model($this->request, $responseData->{$this->getSingularEntity()});
        } catch (ClientException $exception) {
            throw new FortnoxRequestException($exception);
        } catch (ServerException $exception) {
            throw new FortnoxServerException($exception);
        }
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function getSingularEntity()
    {
        return $this->entity_singular;
    }

    public function getUrlEntity()
    {
        return strtolower($this->entity);
    }
}
