<?php

namespace NilPortugues\ForbiddenFunctions\Command;

use Exception;
use NilPortugues\ForbiddenFunctions\Fixer\FileSystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FixerCommand extends Command
{
    /**
     * @var string
     *
     * Command name
     */
    const COMMAND_NAME = 'check';

    const CONFIG_FILE = 'php_forbidden_function.yml';

    /**
     * configure
     */
    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription('Looks into the code using a user-defined list of forbidden function in a given path.')
            ->addArgument(
                'check',
                InputArgument::REQUIRED,
                'File Path'
            )->addArgument(
                'config_file',
                InputArgument::OPTIONAL,
                'Config file'
            );
    }

    /**
     * Execute command
     *
     * @param InputInterface  $input  Input
     * @param OutputInterface $output Output
     *
     * @return \int|\null|void
     *
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('check');

        if (file_exists(self::CONFIG_FILE)) {
            $fileSystem = new FileSystem();

            $forbiddenFunctions = ['file_exists'];
            $errors = [];
            foreach ($fileSystem->getFilesFromPath($path) as $file) {

                $handle = \fopen($file, 'r');
                $lineNumber = 1;
                while (($line = \fgets($handle)) !== \false) {
                    foreach ($forbiddenFunctions as $function) {
                        if (\false !== \strpos($line, $function.'(')) {
                            $errors[$file][] = \sprintf(
                                'Forbidden function \'%s\' found on line %s.',
                                $function,
                                $lineNumber
                            );
                        }
                    }
                    ++$lineNumber;
                }
            }

            if (!empty($errors)) {
                echo 'Forbidden functions were found:'.PHP_EOL.PHP_EOL;
                \print_r($errors);
                echo PHP_EOL;

                return 1;
            }
            echo 'No forbidden functions found'.PHP_EOL.PHP_EOL;
        } else {
            file_put_contents('', self::CONFIG_FILE);
        }




        $output->write(sprintf("\nNo forbidden functions defined in  %s file found.\n", self::CONFIG_FILE), \true);
        return $output;
    }
}
