<?php
namespace App\CLI;

use App\Client\ApiClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'group:add',
    description: 'Creates a new group.',
)]
class CreateGroupCommand extends Command
{
    public function __construct(
        private ApiClient $client
    ) {
        parent::__construct();
        $this->client->debug = false;
    }
  
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        $args['body'] = [
            'name' => $name,
        ];
        try {
            $response = $this->client->GroupAdd($args);
            $output->writeln("Group added successfully with id " . $response->id);
            return Command::SUCCESS;
        }
        catch (ApiClientException $e) {
            return Command::FAILURE;
        }
    }
    
    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the user.')
        ;
    }
}