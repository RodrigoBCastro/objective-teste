<?php

declare(strict_types=1);

namespace App\Command;

use App\Game\Game;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GameCommand extends Command
{
    private Game $game;

    public function __construct
    (
        Game $game
    ) {
        parent::__construct();

        $this->game = $game;
    }

    protected function configure()
    {
        $this->setName('game')
            ->setAliases(['start']);
    }

    protected function execute()
    {
        $this->game->start();

        return Command::SUCCESS;
    }
}