<?php

namespace StatisticsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StatisticsController extends Controller
{
    /**
     * @Route("/")
	 * @Template()
     */
    public function indexAction()
    {
		return [];
    }
}
