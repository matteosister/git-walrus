<?php
/**
 * User: matteo
 * Date: 08/11/13
 * Time: 23.04
 * Just for fun...
 */


namespace CypressLab\GitElephantRestApi\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Class ServerCommand
 */
class ServerCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('ge:server')
            ->setDescription('git elephant rest api server');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pb = new ProcessBuilder(['/usr/bin/php', '-S', 'localhost:8000']);
        $pb->setWorkingDirectory(__DIR__.'/../../../../public');
        $process = $pb->getProcess();
        $process->run(function ($type, $buffer) use ($output) {
            if (Process::ERR === $type) {
                $output->writeln(sprintf('<error>%s</error>', $buffer));
            } else {
                echo 'OUT > '.$buffer;
            }
        });
    }
}
