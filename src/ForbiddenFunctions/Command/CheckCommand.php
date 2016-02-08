<?php

namespace NilPortugues\ForbiddenFunctions\Command;

use NilPortugues\ForbiddenFunctions\Command\Exceptions\ConfigFileException;
use NilPortugues\ForbiddenFunctions\Command\Exceptions\RuntimeException;
use NilPortugues\ForbiddenFunctions\Command\CheckTarget\CheckTarget;
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
            ->addArgument('check', InputArgument::OPTIONAL, 'File Path')
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
        $checkTarget = CheckTarget::create($input->getArgument('check'));
        $configFile = $input->getOption('config');

        if (file_exists($configFile)) {
            $checkTarget->scan(function ($source, $file) use ($configFile) {
                $this->scan($source, $configFile, $file);
            });

            if (!empty($this->errors)) {
                $output->writeln("\nForbidden functions were found:\n");
                foreach ($this->errors as $file => $lines) {
                    foreach ($lines as $line) {
                        $output->writeln(sprintf(" - %s: %s", $checkTarget->getFileReference($file), $line));
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
     * Check given source code for forbidden functions.
     *
     * @param string $source     Source code to be checked.
     * @param string $configFile Configuration file path.
     * @param string $file       Source file path.
     */
    protected function scan($source, $configFile, $file)
    {
        $tokens = token_get_all($source);
        foreach ($tokens as $token) {
            $this->scanForForbiddenFunctions($configFile, $token, $file);
        }
    }

    /**
     * @param $configFile
     * @param $token
     * @param $file
     */
    protected function scanForForbiddenFunctions($configFile, $token, $file)
    {
        if (is_array($token) && count($token) === 3) {
            $type = $token[0];
            $code = $token[1];
            $lineNumber = $token[2];

            if (\false === in_array($type, [T_COMMENT, T_DOC_COMMENT], \true)) {
                foreach ($this->getForbiddenFunctions($configFile) as $function) {
                    if (\strtolower($code) === \strtolower($function)) {
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
