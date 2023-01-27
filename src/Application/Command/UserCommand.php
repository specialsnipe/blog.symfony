<?php

declare(strict_types=1);

namespace App\Application\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class UserCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('user:create')
            ->setDescription('Create a test user.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = $this->getHelper('question');

        $email = $questionHelper->ask($input, $output, new Question('<info>Email: </info>'));
        $password = $questionHelper->ask($input, $output, new Question('<info>Password: </info>'));
        $name = $questionHelper->ask($input, $output, new Question('<info>Name: </info>'));

        $output->writeln(
            sprintf('<comment>Имя пользователя - %s, электронный адрес - %s, пароль - %s</comment>',
                $name, $email, $password
            )
        );

        return Command::SUCCESS;
    }
}
