<?php

namespace AppBundle\Clusterpoint;

class ClusterpointConnectionFactory extends \CPS_Connection {

	private $name;
	private $password;
	private $address;
	private $id;

	public function __construct($name, $password, $address, $id)
	{
		$this->name = $name;
		$this->password = $password;
		$this->address = $address;
		$this->id = $id;
	}

	public function getConnection($database)
	{
		return new \CPS_Connection(
			new \CPS_LoadBalancer($this->address),
			$this->database,
			$this->name,
			$this->password,
			'document',
			'//document/id',
			array('account' => $this->id)
		);
	}
}