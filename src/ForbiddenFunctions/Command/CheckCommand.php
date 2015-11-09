<?php

namespace NilPortugues\ForbiddenFunctions\Command;

use NilPortugues\ForbiddenFunctions\Command\Exceptions\ConfigFileException;
use NilPortugues\ForbiddenFunctions\Command\Exceptions\RuntimeException;
use NilPortugues\ForbiddenFunctions\Checker\FileSystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class CheckCommand extends Command
{
    const COMMAND_NAME = 'check';
    const CONFIG_FILE = 'php_forbidden_function.yml';

    /**
     * @var array
     */
    private static $forbiddenFunctions = [];

    /**
     * @var array
     */
    private $errors = [];

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription('Looks into the code using a user-defined list of forbidden function in a given path.')
            ->addArgument('check', InputArgument::REQUIRED, 'File Path')
            ->addOption('config', '-c', InputOption::VALUE_OPTIONAL, 'Config File', self::CONFIG_FILE);
    }

    /**
     * Execute command
     *
     * @param InputInterface  $input  Input
     * @param OutputInterface $output Output
     *
     * @return \int|\null|void
     *
     * @throws ConfigFileException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $input->getArgument('check');
        $realPath = realpath($path);
        $configFile = $input->getOption('config');

        if (file_exists($configFile)) {
            $this->scanFiles($path, $configFile);

            if (!empty($this->errors)) {
                $output->writeln("\nForbidden functions were found:\n");
                foreach($this->errors as $file => $lines) {
                    foreach($lines as $line) {
                        $output->writeln(sprintf(" - .%s: %s", str_replace($realPath, '', $file), $line));
                    }
                }
                $output->writeln('');
                throw new \Exception('Fix your code');
            }
            return $output->writeln("\nCongratulations! No forbidden functions found.\n");
        }

        throw new ConfigFileException($configFile);
    }

    /**
     * @return mixed
     */
    private function getForbiddenFunctions($file)
    {
        if (!empty(self::$forbiddenFunctions)) {
            return self::$forbiddenFunctions;
        }

        $yaml = new Yaml();
        $functions = $yaml->parse($file, true);

        if (!array_key_exists('forbidden', $functions)) {
            throw new RuntimeException();
        }
        self::$forbiddenFunctions = (array) $functions['forbidden'];


        return self::$forbiddenFunctions;
    }

    /**
     * @param $path
     * @param $configFile
     */
    protected function scanFiles($path, $configFile)
    {
        $fileSystem = new FileSystem();
        foreach ($fileSystem->getFilesFromPath($path) as $file) {
            $tokens = token_get_all(file_get_contents($file));
            foreach($tokens as $token) {
                if(is_array($token) && count($token) === 3) {
                    $type = $token[0];
                    $code = $token[1];
                    $lineNumber = $token[2];

                    if (false === in_array($type, [T_COMMENT, T_DOC_COMMENT], true)) {
                        foreach ($this->getForbiddenFunctions($configFile) as $function) {
                            if (\false !== \strpos($code, $function)) {
                                $this->errors[$file][] = \sprintf(
                                    'Forbidden function \'%s\' found on line %s.',
                                    $function,
                                    $lineNumber
                                );
                            }
                        }
                    }
                }

            }
        }
    }
}
