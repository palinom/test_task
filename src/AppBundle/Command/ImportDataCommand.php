<?php

namespace AppBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ImportDataCommand extends ContainerAwareCommand
{
    protected function configure()
    {

        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:import_csv')
            ->setDescription('Import data to database.')
            ->setHelp('This command allows you to import a required data to database')
            ->addArgument('filename', InputArgument::REQUIRED, 'Enter the filename: ')
            ->addOption(
                'test',
                null,
                InputOption::VALUE_NONE,
                'Test mode',
                null
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $output->writeln([
            'Import CSV files',
            '=================',
            '',
        ]);

        $output->writeln('Whoa!');

        {
            $filename = $input->getArgument('filename');
            $workflowOrganizer = $this->getContainer()->get('workflow.organizer');
            $result = $workflowOrganizer->processCSVFile(new \SplFileObject($filename));

            $output->writeln('Records were processed: ' . $result['result']->getTotalProcessedCount());
            $output->writeln('Records were successful: ' .
                ($result['result']->getTotalProcessedCount() - count($result['failedOne'])));
            $output->writeln('Records were failed: ' . count($result['failedOne']));
            $output->writeln('Records were skipped: ');
            foreach ($result['failedOne'] as $item) {
                $output->writeln($item['productCode']);
            }

            $output->writeln('Records successfully imported!');

        }
    }
}