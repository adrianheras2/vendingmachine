<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use GuzzleHttp;

class VendingmachineCommand extends Command
{
    protected static $defaultName = 'vendingmachine';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $http = new GuzzleHttp\Client(['base_uri' => 'http://localhost:80/']);
        $io = new SymfonyStyle($input, $output);
        $io->note(sprintf("Enter your vending machine actions"));
        $io->note(sprintf("Click ENTER to STOP"));
        $helper = $this->getHelper('question');

        $question = new Question('> ');

        $stop = false;
        while (!$stop) {

            $question->setValidator(function ($answer) {
                if (false) {
                    throw new \RuntimeException('The introduced data is not valid. Please try again');
                }
                return $answer;
            });

            $actions = $helper->ask($input, $output, $question);

            if ($actions === NULL){
                $stop = true;
                continue;
            }

            try {
                $response = $http->request('GET', '/api/vendingmachine/actions/' . $actions);
                echo "\n" . json_decode($response->getBody())->result . "\n\n";
            } catch (\Exception $e){
                echo "\n The data you have entered is not valid \n\n";
            }
        }

        $io->success('Finished using the vending machine!!');

        return Command::SUCCESS;
    }
}
