<?php

namespace App\Command;

use App\Event\EventChangeState;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class EventArchiveCommand extends Command
{
    protected static $defaultName = 'app:event:archive';

    private $eventChangeState;

    public function __construct(EventChangeState $eventChangeState)
    {
        $this->eventChangeState = $eventChangeState;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Archives events which are a month old');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->eventChangeState->archiveEvents();

        $io->success('The old events have been marked as archived');

        return 0;
    }
}
