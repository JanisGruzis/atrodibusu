<?php

namespace AppBundle\Clusterpoint;

class ClusterpointConnection extends \CPS_Connection {

	public function __construct($database)
	{
		$connectionStrings = array(
			'tcp://78.154.146.20:9007',
			'tcp://78.154.146.21:9007',
			'tcp://78.154.146.22:9007',
			'tcp://78.154.146.23:9007',
		);

		parent::__construct(
			new \CPS_LoadBalancer($connectionStrings),
			$database,
			"janis.gruzis@kotique.lv",
			"YmYYkB4W",
			'document',
			'//document/id',
			array('account' => 354)
		);
	}
}