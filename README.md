Laminas Developer Tools
=====================

Module for developer and debug tools for working with the Laminas MVC layer.
While this is still an early version, it is planned to be finished before Laminas
Framework 2.0 stable.


Install
=======
1. Add the `Laminas\\DeveloperTools` module to the module section of your
   application.config.php
2. Copy `LaminasDeveloperTools/config/laminas-developer-tools.local.php.dist` to
   `./config/autoload/laminas-developer-tools.local.php`. Change the settings
   if you like to.
3. Add the following in your `index.php`:
   ```
   define('REQUEST_MICROTIME', microtime(true));
   ```

> **Note:** The displayed execution time in the toolbar will be highly inaccurate
            if you don't define `REQUEST_MICROTIME`.


If you wish to profile Laminas\Db, you have to install and enable [BjyProfiler](https://github.com/bjyoungblood/BjyProfiler).
You can do so by running composer's `require` command.

    php composer.phar require bjyoungblood/BjyProfiler:dev-master

Laminas Developer Tools will try to grab the Profiler from your Laminas\Db adapter
instance, using the `Laminas\Db\Adapter\Adapter` or `Laminas\Db\Adapter\ProfilingAdapter`
service name.
