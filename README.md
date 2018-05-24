# Framework v1.6.4
Build awesome application using this light but powerful micro framework.

## Installation
* Create project via composer. Use command `composer require rodrigoiii/framework`.
* Create virtual host then make the folder public as root of your project.
* Then test it in any browser you have available to see if it is working.

## Docs
The docs is under maintenance.

## CHANGELOGS
Framework v1.6.4
* Update the dependency

Framework v1.6.2
* rodrigoiii/framework-core v1.4.2

Framework v1.6.1
* Remove queue-job inside of framework. Make this as 3rd party library.

Framework v1.6
* Remove Special Commands folder in tests directory.
* Quick Crud command
* Use datatable plugin instead of Pagination php package.
* Apply utility Datatable class.
* Implement Queue Job.
* Add upload function to upload file.
* Add alias for Rapid Authentication Library.
* The framework is now compatible from php version 5.6.30 above.
* Map the structure of tables to model to avoid excessing from length of fields.
* Add shortcut command for web-dev-tools.
* Remove testing environment.
* Fix potential security vulnerability.

Framework v1.5
* Update web-dev-tools node package.
* Update composer.lock

Framework v1.4.1
* Update composer.lock
* Remove .env.testing and phpunit.xml files.
* Fix outdated library.

Framework v1.4
* Separate core of the framework into another repository.

Framework v1.3.2
* Add files db/migration/.gitkeep db/seeds/.gitkeep
* Add loading button when submitting form

Framework v1.3
* Snippet for Auth Slim Library v1.2.0

Framework v1.2
* Implement web mode.
* Fix config/twig-view, cache must be either false or absolute path for to save cache.
* Add option for creating email template.
* Add storage/cache folder.

Framework v1.1.1
* Add USE_DIST environment.
* Change default value of DEBUG_BAR_ON and DEBUG_ON config.
* Add command delete:dist to rollback the command build:dist.

Framework v1.1
* Use web-dev-tools library. Checkout the library <a href="https://www.npmjs.com/package/web-dev-tools">https://www.npmjs.com/package/web-dev-tools</a>.
* Add BuildDistCommand command to run some commands that need to be execute.

Framework v1.0
* Update the README.md.
* Add Twig function config.

## License
My Framework is released under the MIT Licence. See the bundled LICENSE file for details.