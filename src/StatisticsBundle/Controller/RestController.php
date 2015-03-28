<?php
/**
 * Created by PhpStorm.
 * User: janis_gruzis
 * Date: 15.28.3
 * Time: 00:59
 */

namespace StatisticsBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/rest")
 */
class RestController extends Controller
{
	/**
	 * @Route("/transport/list/{type}")
	 */
	public function transportListAction($type)
	{
		$repo = $this->getRepository('StatisticsBundle:Transport');
		$data = $this->toJson($repo->findBy(['type' => $type]));
		return new Response($data, 200, [
			'Content-Type' => 'application/json'
		]);
	}

	/**
	 * @Route("/route/list/{transportId}")
	 */
	public function routeListAction($transportId)
	{
		$repo = $this->getRepository('StatisticsBundle:Route');
		$data = $this->toJson($repo->findBy(['transport' => $transportId]));
		return new Response($data, 200, [
			'Content-Type' => 'application/json'
		]);
	}

	/**
	 * @Route("/stop/list/{routeId}")
	 */
	public function stopListAction($routeId)
	{
		/* @var EntityRepository $repo */
		$repo = $this->getRepository('StatisticsBundle:Stop');
		$data = $repo->createQueryBuilder('s')
			->leftJoin('s.routeStops', 'rs')
			->where('rs.route = :route')
			->orderBy('rs.position', 'asc')
			->setParameter(':route', $routeId)
			->getQuery()
			->getResult();

		$data = $this->toJson($data);
		return new Response($data, 200, [
			'Content-Type' => 'application/json'
		]);
	}

	/**
	 * @Route("/time/list/{routeId}/{stopId}")
	 */
	public function timeListAction($routeId, $stopId)
	{
		/* @var EntityRepository $repo */
		$repo = $this->getRepository('StatisticsBundle:Time');
		$data = $repo->findBy([
			'route' => $routeId,
			'stop' => $stopId,
		]);

		$data = $this->toJson($data);
		return new Response($data, 200, [
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