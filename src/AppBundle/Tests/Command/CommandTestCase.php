<?php

namespace AppBundle\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\StreamOutput;

/**
 * Base class for testing the CLI tools.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
abstract class CommandTestCase extends WebTestCase
{
    /**
     * Runs a command and returns it output.
     *
     * @param $commandString
     *
     * @return string
     */
    public function runCommand($commandString)
    {
        $kernel = static::createKernel(['environment' => 'test', 'debug' => true]);
        $application = new Application($kernel);
        $application->setAutoExit(false);

        $input = new StringInput($commandString);

        $output = new BufferedOutput();

        $application->run($input, $output);
        $content = $output->fetch();

        return $content;
    }
}
