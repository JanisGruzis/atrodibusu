<?php
/**
 * Created by PhpStorm.
 * User: janis_gruzis
 * Date: 15.27.3
 * Time: 18:00
 */

namespace StatisticsBundle\Command;


use StatisticsBundle\Entity\Statistics;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EtalonReadCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('etalon:read')
			->addArgument('offset', InputArgument::REQUIRED, '')
			->addArgument('times', InputArgument::REQUIRED, '')
		;
	}

	public function execute(InputInterface $input, OutputInterface $output)
	{
		$offset = intval($input->getArgument('offset'));
		$timesToRun = intval($input->getArgument('times'));

		$doctrine = $this->getContainer()->get('doctrine');
		$em = $doctrine->getManager();

		$routeRepo = $em->getRepository('StatisticsBundle:Route');
		$timeRepo = $em->getRepository('StatisticsBundle:Time');
		$statRepo = $em->getRepository('StatisticsBundle:Statistics');
		$tmp = $statRepo->createQueryBuilder('s')
			->select('s, t')
			->innerJoin('s.time', 't')
		;
		$statistics = [];
		foreach ($tmp as $stat)
		{
			$statistics[$stat->getTime()->getId()] = $stat;
		}

		/* @var \CPS_Connection */
		$cp = $this->getContainer()->get('clusterpoint');
		$conn = $cp->getConnection('etalon_data');
		$simple = new \CPS_Simple($conn);

		$limit = 20;
		$prev = null;

		for ($i5 = 0; $i5 < $timesToRun; ++$i5)
		{
			$ordering = [];
			$ordering[] = CPS_NumericOrdering('GarNr', 'ascending');
			$ordering[] = CPS_NumericOrdering('Laiks', 'ascending');
			$documents = $simple->search('<GarNr>~GarNr</GarNr>', $offset, $limit, null, $ordering, DOC_TYPE_ARRAY);
			if (count($documents) < 1) break;

			foreach ($documents as $document)
			{
				$marsrNos = $document['MarsrNos'];
				if ($document['Virziens'] == 'Back')
				{
					$parts = explode(' - ', $marsrNos);
					$parts = array_reverse($parts);
					$marsrNos = implode(' - ', $parts);
				}

				if ($prev === null or $prev['MarsrNos'] != $document['MarsrNos'] or
					$prev['TMarsruts'] != $document['TMarsruts'] or $prev['Virziens'] != $document['Virziens'])
				{
					$routes = $routeRepo->createQueryBuilder('r')
						->innerJoin('r.transport', 't')
						->where('r.name = :routeName')
						->andWhere('t.name = :transportName')
						->setParameters([
							':routeName' => $marsrNos,
							':transportName' => trim($document['TMarsruts'], "A "),
						])
						->getQuery()
						->getResult();

					//echo count($routes) . ' ' . $marsrNos . ' ' . trim($document['TMarsruts'], "A ") . ' ' . $document['Virziens'] . PHP_EOL;

					if (count($routes) != 1) {
						$valid = false;
						continue;
					}

					$route = $routes[0];

					/* ir/nav weakday */
					$laiks = new \DateTime($document['Laiks']);
					$isWeekday = intval($laiks->format('N')) < 6 ? 1 : 0;

					$conn = $em->getConnection();
					$stmt = $conn->query("
						(SELECT
						 reissId
						FROM Time
						 WHERE route_id=".$route->getId()." AND weekday=".$isWeekday."
						GROUP BY reissId
						HAVING MIN(time)<='".$laiks->format('Y-m-d H:i:s')."'
						ORDER BY MIN(time) DESC
						LIMIT 1)
						UNION
						(SELECT
						 reissId
						FROM Time
						 WHERE route_id=".$route->getId()." AND weekday=".$isWeekday."
						GROUP BY reissId
						HAVING MIN(time)>='".$laiks->format('Y-m-d H:i:s')."'
						ORDER BY MIN(time) ASC
						LIMIT 1)
						LIMIT 1
					"
					);
					$stmt->execute();
					$res = $stmt->fetchAll();

					if (count($res) > 0)
					{
						$reissId = intval($res[0]['reissId']);
						$times = $timeRepo->findBy(['reissId' => $reissId], ['time' => 'asc']);
						$i = 0;
					} else {
						$valid = false;
						continue;
					}
					$valid = true;
					$prev = $document;
				}

				if(!$valid || count($times)==0) continue;

				while (count($times) - 1 > $i and $laiks < $times[$i + 1]->getTime())
				{
					$i+=1;
				}

				if (!isset($statistics[$times[$i]->getId()])) {
					$statistics[$times[$i]->getId()] = new Statistics();
					$statistics[$times[$i]->getId()]->setCreatedAt(new \DateTime());
					$statistics[$times[$i]->getId()]->setTime($times[$i]);
				}
				$statistics[$times[$i]->getId()]->setEtalonCount($statistics[$times[$i]->getId()]->getEtalonCount()+1);
			}

			$offset += $limit;
		}

		foreach ($statistics as $stat)
		{
			$em->persist($stat);
		}
		$em->flush();

		$output->writeln('Done.');
	}
}