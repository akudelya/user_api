<?php
namespace App\CLI;

use App\Client\ApiClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'user:delete',
    description: 'Deletes user.',
)]
class DeleteUserCommand extends Command
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
            $response = $this->client->UserDelete($args);
            $output->writeln("User with id={$id} is deleted");
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
            ->addArgument('id', InputArgument::REQUIRED, 'The ID of the user.')
        ;
    }
}