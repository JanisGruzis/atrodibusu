<?php
/**
 * Created by PhpStorm.
 * User: janis_gruzis
 * Date: 15.28.3
 * Time: 00:59
 */

namespace StatisticsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use StatisticsBundle\Entity\Report;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/report")
 */
class ReportController extends Controller
{
	/**
	 * @Route("/")
	 * @Method("POST")
	 */
	public function reportAction(Request $request)
	{
		$data = json_decode($request->getContent(), true);

		if (!is_array($data))
		{
			throw $this->createNotFoundException('Data must be json.');
		}

		$doctrine = $this->getDoctrine();
		$em = $doctrine->getManager();

		if (!isset($data['type']))
		{
			throw $this->createNotFoundException('Type must be set.');
		}

		if (!isset($data['lat']))
		{
			throw $this->createNotFoundException('Latitude not set.');
		}

		if (!isset($data['lng']))
		{
			throw $this->createNotFoundException('Longitude not set.');
		}

		$report = new Report();
		$report
			->setCreatedAt(new \DateTime())
			->setType($data['type'])
			->setLat($data['lat'])
			->setLng($data['lng'])
		;

		$em->persist($report);
		$em->flush();

		return new Response();
	}
}