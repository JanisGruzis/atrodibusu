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

		if (!isset($data['rid']))
		{
			throw $this->createNotFoundException('rid must be set.');
		}

		if (!isset($data['lat']))
		{
			throw $this->createNotFoundException('Latitude not set.');
		}

		if (!isset($data['lng']))
		{
			throw $this->createNotFoundException('Longitude not set.');
		}

		if (!isset($data['tid']))
		{
			throw $this->createNotFoundException('Transport id not set.');
		}

		$transRepo = $this->getRepository('StatisticsBundle:Transport');
		$transport = $transRepo->load(intval($data['tid']));

		$report = new Report();
		$report
			->setCreatedAt(new \DateTime())
			->setType($data['rid'])
			->setLat($data['lat'])
			->setLng($data['lng'])
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

		return new Response(json_encode($repo->findAll()), 200, [
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