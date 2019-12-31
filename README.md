# Laminas Developer Tools

[![Build Status](https://travis-ci.org/laminas/laminas-developer-tools.svg?branch=master)](https://travis-ci.org/laminas/laminas-developer-tools)
[![Coverage Status](https://coveralls.io/repos/github/laminas/laminas-developer-tools/badge.svg?branch=master)](https://coveralls.io/github/laminas/laminas-developer-tools?branch=master)

Module providing debug tools for use with [laminas-mvc](https://docs.laminas.dev/laminas-mvc) applications.

## Installation

1. Install the module via composer by running:

   ```bash
   $ composer require --dev laminas/laminas-developer-tools
   ```

   or download it directly from github and place it in your application's `module/` directory.

2. Add the `Laminas\\DeveloperTools` module to the module section of your `config/application.config.php`.
   Starting with version 1.1.0, if you are using [laminas-component-installer](https://docs.laminas.dev/laminas-component-installer),
   this will be done for you automatically.

3. Copy `./vendor/laminas/laminas-developer-tools/config/laminas-developer-tools.local.php.dist` to
   `./config/autoload/laminas-developer-tools.local.php`. Change any settings in it
   according to your needs.

## Extensions

- [BjyProfiler](https://github.com/bjyoungblood/BjyProfiler) - profile `Laminas\Db` queries
- [OcraServiceManager](https://github.com/Ocramius/OcraServiceManager) - track dependencies within your application
- [SanSessionToolbar](https://github.com/samsonasik/SanSessionToolbar) - preview `Laminas\Session` data
- [ZfSnapEventDebugger](https://github.com/snapshotpl/ZfSnapEventDebugger) - debug events from `Laminas\EventManager`
- [DoctrineORMModule](https://github.com/doctrine/DoctrineORMModule) - profile `DoctrineORM` queries
- [JhuZdtLoggerModule](https://github.com/jhuet/JhuZdtLoggerModule) - log data from `Laminas\Log`
- [aist-git-tools](https://github.com/ma-si/aist-git-tools) - information about current GIT repository
