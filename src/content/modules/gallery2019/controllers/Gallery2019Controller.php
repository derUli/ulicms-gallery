<?php
use Gallery2019\Gallery;
use UliCMS\HTML\Style;

class Gallery2019Controller extends Controller
{
    public const MODULE_NAME = "gallery2019";

    public function uninstall()
    {
        $migrator = new DBMigrator("module/{self::MODULE_NAME}", ModuleHelper::buildModuleRessourcePath(self::MODULE_NAME, "sql/down"));
        $migrator->rollback();
    }

    public function getSettingsLinkText()
    {
        return get_translation("edit");
    }

    public function getSettingsHeadline()
    {
        return get_translation("galleries");
    }

    public function settings()
    {
        return Template::executeModuleTemplate(self::MODULE_NAME, "gallery/list.php");
    }

    public function contentFilter($htmlInput)
    {
        preg_match_all("/\[gallery=([0-9]+)]/", $htmlInput, $match);
        
        if (count($match) > 0) {
            for ($i = 0; $i < count($match[0]); $i ++) {
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
