<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 07/06/2020
 * Time: 12:57 pm
 */

namespace App\Command;


use App\Helper\CloudCache;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CloudRedisTest extends Command
{
    public static $defaultName = 'api:cloud_redis_test';

    /**
     * @var CloudCache
     */
    private $cache;

    public function __construct(CloudCache $cache)
    {
        parent::__construct();
        $this->cache = $cache;
    }

    protected function configure()
    {
        $this->addArgument('key', InputArgument::REQUIRED, 'Key to query');
        $this->addOption('remove', '-r', InputOption::VALUE_NONE,'Remove key');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $key = $input->getArgument('key');
        if($input->getOption('remove')) {
            $this->cache->clearCache($key);
        } else {
            $retval = $this->cache->getCache($key);
            $output->writeln(is_array($retval) ? print_r($retval, true) : $retval);
        }
        $output->writeln('DONE');
    }
}