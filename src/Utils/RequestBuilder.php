<?php

namespace LasseRafn\Fortnox\Utils;

use LasseRafn\Fortnox\Builders\Builder;
use LasseRafn\Fortnox\Exceptions\FortnoxRequestException;
use LasseRafn\Fortnox\Responses\PaginatedResponse;

class RequestBuilder
{
	protected $builder;

	protected $parameters     = [];
	protected $urlAdditions   = [];
	protected $dateFormat     = 'Y-m-d';
	protected $dateTimeFormat = 'Y-m-d H:i';

	public function __construct( Builder $builder )
	{
		$this->builder = $builder;
	}

	/**
	 * Select only some fields.
	 *
	 * @param array|int|string $fields
	 *
	 * @return $this
	 */
	public function select( $fields )
	{
		if ( ! isset( $this->parameters['fields'] ) ) {
			$this->parameters['fields'] = [];
		}

		if ( is_array( $fields ) ) {
			foreach ( (array) $fields as $field ) {
				$this->parameters['fields'][] = $field;
			}
		}
		elseif ( is_string( $fields ) || is_int( $fields ) ) {
			$this->parameters['fields'][] = $fields;
		}

		return $this;
	}

	/**
	 * Limit last modified.
	 *
	 * @param \DateTime $startDate
	 *
	 * @return $this
	 */
	public function lastModified( \DateTime $startDate )
	{
		$this->parameters['lastmodified'] = $startDate->format( $this->dateTimeFormat );

		return $this;
	}

	/**
	 * Limit from a specific date.
	 *
	 * @param \DateTime $startDate
	 *
	 * @return $this
	 */
	public function from( \DateTime $startDate )
	{
		$this->parameters['fromdate'] = $startDate->format( $this->dateFormat );

		return $this;
	}

	/**
	 * Limit to a specific date.
	 *
	 * @param \DateTime $endDate
	 *
	 * @return $this
	 */
	public function to( \DateTime $endDate )
	{
		$this->parameters['todate'] = $endDate->format( $this->dateFormat );

		return $this;
	}

	/**
	 * Limit results to a specific financial year.
	 *
	 * @param $financialYear
	 *
	 * @return $this
	 */
	public function financialYear( $financialYear )
	{
		$this->parameters['financialyear'] = $financialYear;

		return $this;
	}

	/**
	 * Limit results to a specific financial year using DateTime.
	 *
	 * @param \DateTime $financialYear
	 *
	 * @return $this
	 */
	public function financialYearByDate( \DateTime $financialYear )
	{
		$this->parameters['financialyeardate'] = $financialYear->format( $this->dateFormat );

		return $this;
	}

	/**
	 * Used for pagination, to set current page.
	 * Starts at 1.
	 *
	 * @param $page
	 *
	 * @return $this
	 */
	public function page( $page )
	{
		$this->parameters['page'] = $page;

		return $this;
	}

	/**
	 * Used for offsetting, also useful combined with `limit`.
	 *
	 * @param $offset
	 *
	 * @return $this
	 */
	public function offset( $offset )
	{
		$this->parameters['offset'] = $offset;

		return $this;
	}

	/**
	 * Used for pagination and limiting.
	 * Default: 100, max: 500.
	 *
	 * @param $limit
	 *
	 * @return $this
	 */
	public function limit( $limit )
	{
		if ( $limit > 500 ) {
			$limit = 500;
		}

		$this->parameters['limit'] = $limit;

		return $this;
	}

	/**
	 * Add a filter to only show models that are deleted.
	 *
	 * @return $this
	 */
	public function deletedOnly()
	{
		$this->parameters['deletedOnly'] = 'true';

		return $this;
	}

	/**
	 * Remove the filter that only show models that are deleted.
	 *
	 * @return $this
	 */
	public function notDeletedOnly()
	{
		unset( $this->parameters['deletedOnly'] );

		return $this;
	}

	/**
	 * Add a filter to only show models changed since %.
	 *
	 * @param \DateTime $date
	 *
	 * @return $this
	 */
	public function since( \DateTime $date )
	{
		$this->parameters['changesSince'] = $date->format( $this->dateFormat );

		return $this;
	}

	/**
	 * Add one or many filter(s) by any parameter(s).
	 *
	 * @param $key
	 * @param $value
	 *
	 * @return $this
	 */
	public function filter( $key, $value = null )
	{
		if( is_array($key) ) {
			$this->parameters = array_merge( $this->parameters, $key );
		} else {
			$this->parameters[$key] = $value;
		}

		return $this;
	}

	/**
	 * Build URL parameters.
	 *
	 * @return string
	 */
	private function buildParameters()
	{
		$parameters = http_build_query( $this->parameters );

		if ( $parameters !== '' ) {
			$parameters = "?{$parameters}";
		}

		return $parameters;
	}

	/**
	 * Build URL additions.
	 *
	 * @return string
	 */
	private function buildUrlAdditions()
	{
		if ( count( $this->urlAdditions ) === 0 ) {
			return '';
		}

		$urlAddition = '';

		foreach ( $this->urlAdditions as $addition ) {
			$urlAddition .= "/{$addition}";
		}

		return $urlAddition;
	}

	/**
	 * Send a request to the API to get models.
	 *
	 * @return PaginatedResponse
	 */
	public function get()
	{
		$response = $this->builder->get( $this->buildParameters(), $this->buildUrlAdditions() );

		return $response;
	}

	/**
	 * Send a request to the API to get models,
	 * manually paginated to get all objects.
	 *
	 * We specify a minor usleep to prevent some
	 * weird bugs. You can disable this if you
	 * desire, however I ran into trouble with
	 * larger datasets.
	 *
	 * @param bool $sleep
	 *
	 * @return array
	 */
	public function all( $sleep = true )
	{
		$items = [];
		$this->page( 0 );

		$response   = $this->builder->get( $this->buildParameters() );
		$pageExists = true;

		while ( $pageExists && count( $response->items ) > 0 ) {
			foreach ( $response->items as $item ) {
				$items[] = $item;
			}

			$this->page( $this->getPage() + 1 );

			if ( $sleep ) {
				usleep( 200 );
			}

			try {
				$response = $this->builder->get( $this->buildParameters() );
			} catch ( FortnoxRequestException $requestException ) {
				if ( $requestException->fortnoxCode === 2001889 ) {
					$pageExists = false;
				}
			}
		}

		return $items;
	}

	/**
	 * Send a request to the API to get a single model.
	 *
	 * @param $guid
	 *
	 * @return Model|mixed
	 */
	public function find( $guid )
	{
		return $this->builder->find( $guid );
	}

	/**
	 * Creates a model from a data array or Model.
	 * Sends a API request.
	 *
	 * @param array|Model $data
	 *
	 * @return Model
	 */
	public function create( $data = [] )
	{
		if ( $data instanceof Model ) {
			$data = $data->toArray();
		}

		$data = [ $this->builder->getSingularEntity() => $data ];

		return $this->builder->create( $data );
	}

	/**
	 * Returns the set page.
	 *
	 * @return int
	 */
	public function getPage()
	{
		return $this->parameters['page'];
	}

	/**
	 * Returns the perPage.
	 *
	 * @return int
	 */
	public function getPerPage()
	{
		return $this->parameters['pageSize'];
	}

	/**
	 * Returns the fields.
	 *
	 * @return array|null
	 */
	public function getSelectedFields()
	{
		return $this->parameters['fields'] ?? null;
	}

	/**
	 * Returns deletedOnly state.
	 *
	 * @return string
	 */
	public function getDeletedOnlyState()
	{
		return $this->parameters['deletedOnly'] ?? 'false';
	}

	/**
	 * Returns changes since.
	 *
	 * @return string|null
	 */
	public function getSince()
	{
		return $this->parameters['changesSince'] ?? null;
	}

	/**
	 * Returns all parameters as an array.
	 *
	 * @return array
	 */
	public function getParameters()
	{
		return $this->parameters;
	}
}
