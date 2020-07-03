<?php

abstract class Gallery2019BaseTest extends \PHPUnit\Framework\TestCase
{
    protected function setUp(): void
    {
        $migrationDirectory = ModuleHelper::buildModuleRessourcePath(
            Gallery2019Controller::MODULE_NAME,
            "sql/up"
        );
        $migrator = new DBMigrator(
            "module/" . Gallery2019Controller::MODULE_NAME,
            $migrationDirectory
        );
        $migrator->migrate();
    }

    protected function tearDown(): void
    {
        $controller = new Gallery2019Controller();
        $controller->uninstall();
    }
}
