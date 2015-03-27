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

class NormalizeEtalonLaiksCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('update:etalon')
		;
	}

	public function execute(InputInterface $input, OutputInterface $output)
	{
		/* @var \CPS_Connection */
		$cp = $this->getContainer()->get('clusterpoint');
		$simple = new \CPS_Simple($cp);

		$limit = 10000;
		$offset = 0;

		do {
			$request = new \CPS_SearchRequest('<Type>etalon</Type>', $offset, $limit);
			$response = $cp->sendRequest($request);
			$documents = $response->getRawDocuments(DOC_TYPE_ARRAY);

			foreach ($documents as $i => $d) {
				$documents[$i]['Laiks'] = date('Y-m-d H:i:s', strtotime($d['Laiks']));
			}

			$simple->updateMultiple($documents);

			$cnt = count($documents);
			$offset += $cnt;
			$output->writeln('Updated ' . $cnt . ' ' . $offset);
		} while($cnt);

		$output->writeln('Done.');
	}
}