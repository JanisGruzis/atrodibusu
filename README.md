Installation
==============

These are general and probably like 90% of steps you should use to setup basic environment for running symfony2 project. Enjoy.

OS software
-----------

1. Get dat Ubuntu 14.04
1. Install php. `sudo apt-get install php5`
1. Install mysql. `sudo apt-get install mysql-server`
1. Install multiple user permission manager a.k.a. acl. `sudo apt-get install acl`
1. Install composer. `curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin`

App setup and dependencies
--------------------------

1. Download repository content. git pull <address> and stuff. If you want, I can add certificate to repository for faster pull/push.
1. Install vendors (php lib dependencies). `composer install`
1. Setup cache, log permissions. `./permissions.sh`
1. Copy app/config/parameters.yml.dist to app/config/parameters.yml and complete with your data.
1. Create mysql db.
1. Check framework dependencies. `php app/check.php` and fix them.

Running
-------

You can run software with apache (which requires vhost setup and stuff, but symfony is cool and has built in php based dev server). For dat sweet sweet server run:

	php app/console server:run
	
Usage
=====

Some general shortcuts for doing stuff.

Update database with changes
----------------------------

If there are changes in model, run.

	php app/console doctrine:schema:update --force

Create entity for ORM
---------------------

You can create entity class and you can also create it interactively through cli.

	php app/console doctrine:generate:entity
	
Create setter/getter methods
----------------------------

If you are lazy as me you can in entities simply write attribute and run command bellow to generate setter/getter methods and repository for it.

	php app/console doctrine:generate:entities AppBundle
