<?php

namespace LasseRafn\Fortnox\Utils;

class Object
{
	public function __construct($attributes = [])
	{
		foreach($attributes as $key => $value) {
			$this->{$key} = $value;
		}
	}

	public function __toString()
	{
		return json_encode( $this->toArray() );
	}

	/**
	 * Returns an array of public attributes
	 *
	 * @param bool $excludeNull
	 *
	 * @return array
	 */
	public function toArray($excludeNull = true)
	{
		$data       = [];
		$class      = new \ReflectionObject( $this );
		$properties = $class->getProperties( \ReflectionProperty::IS_PUBLIC );

		/** @var \ReflectionProperty $property */
		foreach ( $properties as $property ) {
			if( $excludeNull && $this->{$property->getName()} === null) {
				continue;
			}

			$data[ $property->getName() ] = $this->{$property->getName()};
		}

		return $data;
	}
}