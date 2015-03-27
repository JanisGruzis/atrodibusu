<?php

namespace AppBundle\Clusterpoint;

class ClusterpointConnection extends \CPS_Connection {

	public function __construct($name, $password, $database, $address)
	{
		parent::__construct(
			new \CPS_LoadBalancer($address),
			$database,
			$name,
			$password,
			'document',
			'//document/id',
			array('account' => 354)
		);
	}
}