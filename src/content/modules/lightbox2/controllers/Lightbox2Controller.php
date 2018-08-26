<?php
use UliCMS\HTML\Style;

class Lightbox2Controller extends Controller
{

    public const MODULE_NAME = "lightbox2";

    public function head()
    {
        $cssFile = ModuleHelper::buildRessourcePath(self::MODULE_NAME, "js/lightbox2/css/lightbox.min.css");
        echo Style::FromExternalFile($cssFile);
    }

    public function adminHead()
    {
        $this->head();
    }

    public function frontendFooter()
    {
        $translation = new JSTranslation(array(), "LightBoxTranslation");
        $translation->addKey("image_x_of_y");
        $translation->render();
        
        $jsFile = ModuleHelper::buildRessourcePath(self::MODULE_NAME, "js/lightbox2/js/lightbox.min.js");
        $frontendJs = ModuleHelper::buildRessourcePath(self::MODULE_NAME, "js/frontend.js");
        
        enqueueScriptFile($jsFile);
        enqueueScriptFile($frontendJs);
        
        combinedScriptHtml();
    }

    public function adminFooter()
    {
        $this->frontendFooter();
    }
}