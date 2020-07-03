<?php

declare(strict_types=1);

use Gallery2019\Gallery;

class Gallery2019Controller extends Controller
{
    public const MODULE_NAME = "gallery2019";

    public function uninstall(): void
    {
        $migrator = new DBMigrator(
            "module/{self::MODULE_NAME}",
            ModuleHelper::buildModuleRessourcePath(
                    self::MODULE_NAME,
                    "sql/down"
                )
        );
        $migrator->rollback();
    }

    public function getSettingsLinkText(): string
    {
        return get_translation("edit");
    }

    public function getSettingsHeadline(): string
    {
        return get_translation("galleries");
    }

    public function settings(): string
    {
        return Template::executeModuleTemplate(self::MODULE_NAME, "gallery/list.php");
    }

    public function contentFilter(string $htmlInput): string
    {
        preg_match_all("/\[gallery=([0-9]+)]/", $htmlInput, $match);

        if (count($match) > 0) {
            for ($i = 0; $i < count($match[0]); $i++) {
                $placeholder = $match[0][$i];
                $id = unhtmlspecialchars($match[1][$i]);
                $gallery = new Gallery(intval($id));
                ViewBag::set("gallery", $gallery);
                $html = Template::executeModuleTemplate(self::MODULE_NAME, "show.php");
                $htmlInput = str_replace($placeholder, $html, $htmlInput);
            }
        }

        return $htmlInput;
    }
}
