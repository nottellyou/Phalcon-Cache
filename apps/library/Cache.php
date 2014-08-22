<?php

    /**
     * Phalcon Framework Cache Library
     *
     *
     * PHP version 5
     *
     * LICENSE: This source file is subject to version 3.01 of the PHP license
     * that is available through the world-wide-web at the following URI:
     * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
     * the PHP License and are unable to obtain it through the web, please
     * send a note to license@php.net so we can mail you a copy immediately.
     *
     * @category   Cms
     * @package    Pixarty
     * @author     Beytullah Gurpinar <beytullah.gurpinar@asyamedya.com>
     * @author     Asyamedya <info@asyamedya.com>
     * @copyright  2014 - Asyamedya
     * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
     * @version    1.0
     */

    /*
    * Place includes, constant defines and $_GLOBAL settings here.
    * Make sure they have appropriate docblocks to avoid phpDocumentor
    * construing they are documented by the page-level docblock.
    */

    Class Cache extends Phalcon\Mvc\User\Component {

        private $Cache = '';
        private $config = '';

        function __construct() {

            $this->config = $this->di->get('config');

            if (!$this->config->cache->status) {
                return false;
            }

            if (class_exists('Memcache') && $this->config->cache->memcache) { // if active memcache 

                $frontCache = new Phalcon\Cache\Frontend\Data(array("lifetime" => 172800));

                $this->Cache = new Phalcon\Cache\Backend\Memcache($frontCache, array('host' => 'localhost', 'port' => 11211, 'persistent' => false));
            } else { // or use file cache

                $frontCache = new \Phalcon\Cache\Frontend\Data(array("lifetime" => 3600));

                $this->Cache = new \Phalcon\Cache\Backend\File($frontCache, array("cacheDir" => "../var/cache/data/" // cache folder  /[phalcondir]/var/cache/data
                ));
            }


        }

        public function get($key) {

            if (!$this->config->cache->status) {
                return false;
            }

            return $this->Cache->get(sha1($key));
        }

        public function set($key, $value) {

            if (!$this->config->cache->status) {
                return false;
            }

            $this->Cache->save(sha1($key), $value);
        }

        public function getStatus() {
            return $this->config->cache->status;
        }

    }
