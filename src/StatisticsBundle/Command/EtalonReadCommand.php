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
			->addArgument('time_from', InputArgument::REQUIRED, '')
			->addArgument('time_to', InputArgument::REQUIRED, '');
		;
	}

	public function execute(InputInterface $input, OutputInterface $output)
	{
		$time_from=new \DateTime($input->getArgument('time_from'));
		$time_to=new \DateTime($input->getArgument('time_to'));
		$doctrine = $this->getContainer()->get('doctrine');
		$em = $doctrine->getManager();

		$routeRepo = $em->getRepository('StatisticsBundle:Route');
		$timeRepo = $em->getRepository('StatisticsBundle:Time');
		$statRepo = $em->getRepository('StatisticsBundle:Statistics');
		$tmp = $statRepo->createQueryBuilder('s')
			->select('s, t')
			->innerJoin('s.time', 't')
			->getQuery()
			->getResult()
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

		$limit = 500;
		$prev = null;
		$prev_time = null;
		$max_delta=new \DateInterval('PT1H');
		$step=new \DateInterval('PT1H');

		while($time_from<$time_to)
		{
			$interval=$time_from->format('Y/m/d H:i:s').' .. ';
			$time_from->add($step);
			$interval.=$time_from->format('Y/m/d H:i:s');
			echo "\n".$interval."\n";
			$offset = 0;
			while(true)
			{
				echo ".";
				$ordering = [];
				$ordering[] = CPS_NumericOrdering('GarNr', 'ascending');
				$ordering[] = CPS_DateOrdering('Laiks', 'ascending');
				
				$documents = $simple->search('<GarNr>~GarNr</GarNr><Laiks>'.$interval.'</Laiks>', $offset, $limit, null, $ordering, DOC_TYPE_ARRAY);
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
					
					$prev_time=$time;
					$time=(new \DateTime($document['Laiks']));
					
					if ($prev === null or $prev['MarsrNos'] != $document['MarsrNos'] or
						$prev['TMarsruts'] != $document['TMarsruts'] or $prev['Virziens'] != $document['Virziens'] || ($prev_time!=null && $time->diff($prev_time)>$max_delta)
					{
						$prev = $document;
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
							HAVING MIN(time)<='".$laiks->format('H:i:s')."'
							ORDER BY MIN(time) DESC
							LIMIT 1)
							UNION
							(SELECT
							 reissId
							FROM Time
							 WHERE route_id=".$route->getId()." AND weekday=".$isWeekday."
							GROUP BY reissId
							HAVING MIN(time)>='".$laiks->format('H:i:s')."'
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
						//foreach($times AS $t) echo $t->getTime()->format('H:i:s')."\r\n";
					}

					if(!$valid || count($times)==0) continue;
					$laiks = (new \DateTime($document['Laiks']))->format('H:i:s');
					while (count($times) - 1 > $i and $laiks > $times[$i + 1]->getTime()->format('H:i:s'))
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

		}

		foreach ($statistics as $stat)
		{
			$em->persist($stat);
		}
		$em->flush();

		$output->writeln('Done.');
	}
}