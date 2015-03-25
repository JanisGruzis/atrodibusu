<?php

namespace StatisticsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ivory\GoogleMap\Map;

class StatisticsController extends Controller
{
    /**
     * @Route("/", name="statistics")
	 * @Template()
     */
    public function indexAction()
    {
		$map = $this->get('ivory_google_map.map');

        return [
			'map' => $map,
		];
    }
}
