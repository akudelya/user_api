<?php
namespace App\CLI;

use App\Client\ApiClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'user:update',
    description: 'Updates user data',
)]
class UpdateUserCommand extends Command
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
        $name = $input->getArgument('name');
        $email = $input->getArgument('email');

        $args['body'] = [
            'name' => $name,
            'email' => $email,
        ];
        $args['subst'] = ['{id}' => $id];
        try {
            $response = $this->client->UserUpdate($args);
            $output->writeln("User with id={$response->id} is updated successfully");
            return Command::SUCCESS;
        }
        catch (ApiClientException $e) {
            return Command::FAILURE;
        }
    }
    
    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::REQUIRED, 'The ID of the user.')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the user.')
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the user.')
        ;
    }
}