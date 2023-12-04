# Laminas Developer Tools

[![Build Status](https://github.com/laminas/laminas-developer-tools/actions/workflows/continuous-integration.yml/badge.svg)](https://github.com/laminas/laminas-developer-tools/actions/workflows/continuous-integration.yml)
[![type-coverage](https://shepherd.dev/github/laminas/laminas-developer-tools/coverage.svg)](https://shepherd.dev/github/laminas/laminas-developer-tools)
[![Psalm level](https://shepherd.dev/github/laminas/laminas-developer-tools/level.svg)](https://shepherd.dev/github/laminas/laminas-developer-tools)

> ## 🇷🇺 Русским гражданам
>
> Мы, участники Laminas, родились и живем в разных странах. У многих из нас есть друзья, родственники и коллеги как в России, так и в Украине. Некоторые из нас родились в России. Некоторые из нас живут в России. У некоторых бабушки и дедушки сражались с фашистами во Второй мировой войне. Здесь никто не поддерживает фашизм.
>
> У одного из нас есть украинская родственница, которая спаслась из дома вместе с сыном. Поезд задержался из-за бомбежки на дороге впереди. У нас есть друзья, которые прячутся в бомбоубежищах. Мы с тревогой ждем весточки от них после воздушных налетов, которые беспорядочно наносят удары и попадают по больницам, школам, детским садам и домам. Мы не берем это из каких-либо СМИ. Мы наблюдаем это напрямую.
>
> Вы доверяете нам достаточно, чтоб использовать наши программы, и мы просим вас довериться нам вновь. Мы нуждаемся в помощи. Выходите и протестуйте против этой бесполезной войны. Остановите кровопролитие. Скажите "Нет войне!"
>
> ## 🇺🇸 To Citizens of Russia
>
> We at Laminas come from all over the world. Many of us have friends, family and colleagues in both Russia and Ukraine. Some of us were born in Russia. Some of us currently live in Russia. Some have grandparents who fought Nazis in World War II. Nobody here supports fascism.
>
> One team member has a Ukrainian relative who fled her home with her son. The train was delayed due to bombing on the road ahead. We have friends who are hiding in bomb shelters. We anxiously follow up on them after the air raids, which indiscriminately fire at hospitals, schools, kindergartens and houses. We're not taking this from any media. These are our actual experiences.
>
> You trust us enough to use our software. We ask that you trust us to say the truth on this. We need your help. Go out and protest this unnecessary war. Stop the bloodshed. Say "stop the war!"

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
