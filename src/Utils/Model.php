<?php

namespace LasseRafn\Fortnox\Utils;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use LasseRafn\Fortnox\Exceptions\FortnoxRequestException;
use LasseRafn\Fortnox\Exceptions\FortnoxServerException;

class Model
{
    protected $entity;
    protected $entity_singular;
    protected $primaryKey;
    protected $modelClass = self::class;
    protected $fillable = [];
    protected $request;

    public function __construct(Request $request, $data = [])
    {
        $this->request = $request;

        $data = (array) $data;

        foreach ($data as $attribute => $value) {
            $attribute = is_string($attribute) ? trim($attribute) : $attribute;

            if (!method_exists($this, 'set'.ucfirst($this->camelCase($attribute)).'Attribute')) {
                $this->setAttribute($attribute, $value);
            } else {
                $this->setAttribute($attribute, $this->{'set'.ucfirst($this->camelCase($attribute)).'Attribute'}($value));
            }
        }
    }

    public function __toString()
    {
        return json_encode($this->toArray());
    }

    /**
     * Returns an array of the models public attributes.
     *
     * @return array
     */
    public function toArray()
    {
        $data = [];
        $class = new \ReflectionObject($this);
        $properties = $class->getProperties(\ReflectionProperty::IS_PUBLIC);

        /** @var \ReflectionProperty $property */
        foreach ($properties as $property) {
            $data[$property->getName()] = $this->{$property->getName()};
        }

        return $data;
    }

    /**
     * Set attribute of model.
     *
     * @param $attribute
     * @param $value
     */
    protected function setAttribute($attribute, $value)
    {
        $this->{$attribute} = $value;
    }

    /**
     * Send a request to the API to update the model.
     *
     * @param array $data
     *
     * @return mixed
     */
    public function update($data = [])
    {
        try {
            $response = $this->request->curl->put("{$this->entity}/{$this->{$this->primaryKey}}", [
                'json' => [$this->getSingularEntity() => $data],
            ]);

            $responseData = json_decode($response->getBody()->getContents());

            $this->mergeData($responseData->{$this->getSingularEntity()});

            return new $this->modelClass($this->request, $responseData->{$this->getSingularEntity()});
        } catch (ClientException $exception) {
            throw new FortnoxRequestException($exception);
        } catch (ServerException $exception) {
            throw new FortnoxServerException($exception);
        }
    }

    /**
     * Convert a string to camelCase.
     *
     * @param $string
     *
     * @return mixed
     */
    private function camelCase($string)
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $string));
        $value = str_replace(' ', '', $value);

        return lcfirst($value);
    }

    /**
     * Get PrimaryKey attribute.
     *
     * @return string
     */
    public function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    public function getSingularEntity()
    {
        return $this->entity_singular;
    }

    private function mergeData($newData)
    {
        $newData = (array) $newData;

        foreach ($newData as $attribute => $value) {
            $attribute = is_string($attribute) ? trim($attribute) : $attribute;

            if (!method_exists($this, 'set'.ucfirst($this->camelCase($attribute)).'Attribute')) {
                $this->setAttribute($attribute, $value);
            } else {
                $this->setAttribute($attribute, $this->{'set'.ucfirst($this->camelCase($attribute)).'Attribute'}($value));
            }
        }
    }
}
