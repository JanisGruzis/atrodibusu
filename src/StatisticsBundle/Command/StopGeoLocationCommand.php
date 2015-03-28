<?php
/**
 * Created by PhpStorm.
 * User: janis_gruzis
 * Date: 15.27.3
 * Time: 18:00
 */

namespace StatisticsBundle\Command;


use StatisticsBundle\Entity\Statistics;
use StatisticsBundle\Entity\Stop;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StopGeoLocationCommand extends ContainerAwareCommand
{
	protected function configure()
	{
		$this
			->setName('stop:geolocation')
		;
	}

	public function execute(InputInterface $input, OutputInterface $output)
	{
		$doctrine = $this->getContainer()->get('doctrine');
		$em = $doctrine->getManager();
		$repo = $em->getRepository('StatisticsBundle:Stop');

		$cp = $this->getContainer()->get('clusterpoint_jg');
		$conn = $cp->getConnection('patronage');

		$simple = new \CPS_Simple($conn);
		$documents = $simple->search('<Type>stop</Type>', 0, 2000, null, null, DOC_TYPE_ARRAY);

		$output->writeln('Read docs: ' . count($documents));
		foreach ($documents as $document)
		{
			$stop = $repo->find(intval($document['StopID']));
			if ($stop instanceof Stop) {
				$stop->setLat($document['Lat']);
				$stop->setLng($document['Lng']);
				$em->persist($stop);
				$em->flush();
			}
		}

		$output->writeln('Done.');
	}
}