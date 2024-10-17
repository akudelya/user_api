<?php
namespace App\CLI;

use App\Client\ApiClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'user:add',
    description: 'Creates a new user.',
)]
class CreateUserCommand extends Command
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
        $email = $input->getArgument('email');

        $args['body'] = [
            'name' => $name,
            'email' => $email,
        ];
        try {
            $response = $this->client->UserAdd($args);
            $output->writeln("User added successfully with id " . $response->id);
            // return this if there was no problem running the command
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
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the user.')
        ;
    }
}