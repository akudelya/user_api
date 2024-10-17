<?php
namespace App\CLI;

use App\Client\ApiClient;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'group:add_user',
    description: 'Adds the user to the group.',
)]
class AddUserToGroupCommand extends Command
{
    public function __construct(
        private ApiClient $client
    ) {
        parent::__construct();
        $this->client->debug = true;
    }
  
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $groupId = $input->getArgument('group_id');
        $userId = $input->getArgument('user_id');

        $args['body'] = [
            'user_id' => $userId,
        ];
        $args['subst'] = ['{id}' => $groupId];
        try {
            $response = $this->client->AddUserToGroup($args);
            $output->writeln("User added successfully to group");
            return Command::SUCCESS;
        }
        catch (ApiClientException $e) {
            return Command::FAILURE;
        }
    }
    
    protected function configure(): void
    {
        $this
            ->addArgument('group_id', InputArgument::REQUIRED, 'The ID of the group.')
            ->addArgument('user_id', InputArgument::REQUIRED, 'The ID of the user.')
        ;
    }
}