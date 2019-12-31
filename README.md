Laminas Developer Tools
====================

[![Build Status](https://travis-ci.org/laminas/LaminasDeveloperTools.svg)](https://travis-ci.org/laminas/LaminasDeveloperTools)  

Module providing debug tools for working with the [Laminas](https://github.com/laminas/laminas) MVC
layer.

Installation
============

1. Install the module via composer by running:

   ```sh
   composer require laminas/laminas-developer-tools:dev-master
   ```
   or download it directly from github and place it in your application's `module/` directory.
2. Add the `Laminas\\DeveloperTools` module to the module section of your `config/application.config.php`
3. Copy `./vendor/laminas/laminas-developer-tools/config/laminas-developer-tools.local.php.dist` to
   `./config/autoload/laminas-developer-tools.local.php`. Change any settings in it
   according to your needs.
4. If server version of PHP is lower than 5.4.0 add the following in your `index.php`:
   ```php
   define('REQUEST_MICROTIME', microtime(true));
   ```

   **Note:** The displayed execution time in the toolbar will be highly inaccurate
    if you don't define `REQUEST_MICROTIME` in PHP < 5.4.0.

Extensions
==========

* [BjyProfiler](https://github.com/bjyoungblood/BjyProfiler) - profile `Laminas\Db` queries
* [OcraServiceManager](https://github.com/Ocramius/OcraServiceManager) - track dependencies within your application
* [SanSessionToolbar](https://github.com/samsonasik/SanSessionToolbar) - preview `Laminas\Session` data
* [ZfSnapEventDebugger](https://github.com/snapshotpl/ZfSnapEventDebugger) - debug events from `Laminas\EventManager`
* [DoctrineORMModule](https://github.com/doctrine/DoctrineORMModule) - profile `DoctrineORM` queries
* [JhuZdtLoggerModule](https://github.com/jhuet/JhuZdtLoggerModule) - log data from `Laminas\Log`
* [aist-git-tools](https://github.com/ma-si/aist-git-tools) - information about current GIT repository
