<?php
/**
 * Created by PhpStorm.
 * User: janis_gruzis
 * Date: 15.27.3
 * Time: 18:00
 */

namespace StatisticsBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EtalonReadCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('etalon:read')
		;
	}

	public function execute(InputInterface $input, OutputInterface $output)
	{
		$doctrine = $this->getContainer()->get('doctrine');
		$em = $doctrine->getManager();

		$routeRepo = $em->getRepository('StatisticsBundle:Route');

		/* @var \CPS_Connection */
		$cp = $this->getContainer()->get('clusterpoint');
		$conn = $cp->getConnection('etalon_data');
		$simple = new \CPS_Simple($conn);

		$offset = 100000;
		$limit = 100;
		$prev = null;

		do {
			$ordering = [];
			$ordering[] = CPS_NumericOrdering('GarNr', 'ascending');
			$ordering[] = CPS_NumericOrdering('Laiks', 'ascending');
			$documents = $simple->search('<GarNr>~GarNr</GarNr>', $offset, $limit, null, $ordering, DOC_TYPE_ARRAY);


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

					echo count($routes) . ' ' . $marsrNos . ' ' . trim($document['TMarsruts'], "A ") . ' ' . $document['Virziens'] . PHP_EOL;

					if (count($routes) != 1) {
						continue;
					}

					$route = $routes[0];
				}
			}

			$offset += $limit;
		} while(count($documents) > 0);

		$output->writeln('Done.');
	}
}