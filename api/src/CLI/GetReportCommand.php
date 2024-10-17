<?php
namespace App\CLI;

use App\Client\ApiClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'group:report',
    description: 'Retrieves data for all groups.',
)]
class GetReportCommand extends Command
{
    public function __construct(
        private ApiClient $client
    ) {
        parent::__construct();
        $this->client->debug = false;
    }
  
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $response = $this->client->GroupReport([]);
            $output->writeln(print_r((array)$response, true));
            return Command::SUCCESS;
        }
        catch (ApiClientException $e) {
            return Command::FAILURE;
        }
    }
    
}