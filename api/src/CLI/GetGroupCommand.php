<?php
namespace App\CLI;

use App\Client\ApiClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'group:get',
    description: 'Retrieves data of group.',
)]
class GetGroupCommand extends Command
{
    public function __construct(
        private ApiClient $client
    ) {
        parent::__construct();
        $this->client->debug = false;
    }
  
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $id = $input->getArgument('id');

        $args['subst'] = ['{id}' => $id];
        try {
            $response = $this->client->GroupGet($args);
            $output->writeln(print_r((array)$response, true));
            return Command::SUCCESS;
        }
        catch (ApiClientException $e) {
            return Command::FAILURE;
        }
    }
    
    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::REQUIRED, 'The ID of the group.')
        ;
    }
}