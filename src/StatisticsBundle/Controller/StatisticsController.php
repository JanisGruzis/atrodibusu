<?php

namespace StatisticsBundle\Controller;

use AppBundle\Clusterpoint\ClusterpointConnection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StatisticsController extends Controller
{
    /**
     * @Route("/", name="statistics")
	 * @Template()
     */
    public function indexAction()
    {
		$connection = new ClusterpointConnection('etalon');
		$request = new \CPS_SearchRequest('*', 0, 100);
		$response = $connection->sendRequest($request);
		$documents = $response->getRawDocuments(DOC_TYPE_ARRAY);

		return [
			'etalons' => $documents
		];
    }
}
