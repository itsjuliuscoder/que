<?php
/**
 * Created by PhpStorm.
 * User: Wisdom Emenike
 * Date: 2/5/2019
 * Time: 11:02 AM
 */

namespace que\session;

use que\session\type\Files;
use que\session\type\QueKip;
use que\session\type\Memcached as Memcached;
use que\session\type\Redis as RedisCache;

class Session
{

    /**
     * @var Session
     */
    private static Session $instance;

    /**
     * @var string
     */
    private static string $package_name;

    protected function __construct()
    {
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    private function __wakeup()
    {
        // TODO: Implement __wakeup() method.
    }

    /**
     * @return Session
     */
    public static function getInstance(): Session
    {
        if (!isset(self::$instance))
            self::$instance = new self;
        return self::$instance;
    }

    /**
     * @param string|null $session_id
     * @return string
     */
    public static function getSessionID(string $session_id = null) {
        self::$package_name = config('session.partition', APP_PACKAGE_NAME);
        return self::$package_name . "-session-id:" . wordwrap($session_id ?: session_id(), 4, ":", true);
    }

    /**
     * @return Files
     */
    public function getFiles(): Files {
        return Files::getInstance();
    }

    /**
     * @return Memcached
     */
    public function getMemcached(): Memcached {
        return Memcached::getInstance(self::getSessionID());
    }

    /**
     * @return RedisCache
     */
    public function getRedis(): RedisCache {
        return RedisCache::getInstance(self::getSessionID());
    }

    /**
     * @return QueKip
     */
    public function getQueKip(): QueKip {
        return QueKip::getInstance(self::getSessionID());
    }

}