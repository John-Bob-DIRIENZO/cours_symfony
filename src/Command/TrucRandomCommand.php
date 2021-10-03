<?php

namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TrucRandomCommand extends Command
{
    protected static $defaultName = 'app:truc-random';
    protected static $defaultDescription = 'Je vais dire des choses aléatoires';
    private $logger;

    public function __construct(string $name = null, LoggerInterface $logger)
    {
        parent::__construct($name);
        $this->logger = $logger;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('prenom', InputArgument::OPTIONAL, 'Votre prénom ici')
            ->addOption('uppercase', null, InputOption::VALUE_NONE, 'Je vais crier')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $prenom = $input->getArgument('prenom');

        if ($prenom) {
            $io->note(sprintf('Bonjour: %s', $prenom));
        }

        $randoms = [
            'francis',
            'saucisse',
            'chaise',
            'souris'
        ];

        $random = $randoms[array_rand($randoms)];

        if ($input->getOption('uppercase')) {
            $random = strtoupper($random);
        }

        $io->success($random);

        return Command::SUCCESS;
    }
}
