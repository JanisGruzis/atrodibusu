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

class ReportController extends Controller
{
	/**
	 * @Route("/report")
	 */
	public function reportAction(Request $request)
	{
		$rid = $request->query->get('rid');
		$lat = $request->query->get('lat');
		$lng = $request->query->get('lng');
		$transportId = $request->query->get('tid');

		if ($rid == null || $lat == null || $lng == null || $transportId == null)
		{
			throw $this->createNotFoundException('Arguments');
		}

		$doctrine = $this->getDoctrine();
		$em = $doctrine->getManager();

		$transRepo = $this->getRepository('StatisticsBundle:Transport');
		$transport = $transRepo->find($transportId);

		$report = new Report();
		$report
			->setCreatedAt(new \DateTime())
			->setType($rid)
			->setLat($lat)
			->setLng($lng)
			->setTransport($transport)
		;

		$em->persist($report);
		$em->flush();

		return new Response();
	}

	/**
	 * @Route("/rest/report")
	 */
	public function getAction()
	{
		$repo = $this->getRepository("StatisticsBundle:Report");

		return new Response($this->toJson($repo->findAll()), 200, [
			'Content-Type' => 'application/json'
		]);
	}

	/**
	 * Get repository/
	 * @param $repo
	 * @return mixed
	 */
	private function getRepository($repo)
	{
		$doctrine = $this->getDoctrine();
		$em = $doctrine->getManager();
		return $em->getRepository($repo);
	}

	/**
	 * To json.
	 * @param $data
	 * @return mixed
	 */
	private function toJson($data)
	{
		$serializer = $this->get('jms_serializer');
		return $serializer->serialize($data, 'json');
	}
}