<?php
namespace App\Commands;

use App\Event\EventChangeState;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateEventCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:update-db';

    private $eventChangeState;

    public function __construct(EventChangeState $eventChangeState)
    {
        $this->eventChangeState = $eventChangeState;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setHelp('Cette commande met à jour la base de donnée');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->eventChangeState->changeState();


        return 0;
    }
}