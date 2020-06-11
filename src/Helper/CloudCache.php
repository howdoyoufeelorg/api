<?php
/**
 * Created by Viktor Kostadinov (viktor.kostadinov@gmail.com)
 * Date: 07/06/2020
 * Time: 11:52 am
 */

namespace App\Helper;

use Redis;

class CloudCache
{
    const ENV_REDIS_HOST = 'REDISHOST';
    const ENV_REDIS_PORT = 'REDISPORT';

    const CACHE_KEY_QUESTIONS = 'hdyf_questions';
    const CACHE_KEY_INSTRUCTIONS = 'hdyf_instructions';

    public function setCache(string $key, $value, int $timeout = 0)
    {
        $redis = $this->getRedis();
        if($timeout) {
            $redis->setex($key, $timeout, $value);
        } else {
            $redis->set($key, $value);
        }
        $redis->close();
    }

    public function getCache(string $key)
    {
        $redis = $this->getRedis();
        $value = $redis->get($key);
        $redis->close();
        return $value;
    }

    public function clearCache(string $key)
    {
        $redis = $this->getRedis();
        $redis->del($key);
        $redis->close();
    }

    private function getRedis()
    {
        $redis = new Redis();
        $redis->connect(getenv(self::ENV_REDIS_HOST), getenv(self::ENV_REDIS_PORT));
        $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
        return $redis;
    }

    public function constructInstructionsKey(string $zipcode, string $severity)
    {
        return self::CACHE_KEY_INSTRUCTIONS.":${zipcode}:${severity}";
    }
}