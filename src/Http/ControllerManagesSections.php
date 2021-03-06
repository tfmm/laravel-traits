<?php

namespace MallardDuck\LaravelTraits\Http;

/**
 * Adds Blade section managmenet to controllers.
 */
trait ControllerManagesSections
{

    /**
     * @var array
     */
    protected $bladeSections = [];

    /**
     * @var bool
     */
    private $sectionsProcessed = false;

    /**
     * @var array
     */
    private $orgBladeSections = [];

    /**
     * Boots the trait and sets the constructor defined section data.
     */
    public function bootManagesSections()
    {
        $this->setControllerSections();
    }

    /**
     * Helper method to loop thru the controller set Blade sections.
     */
    public function setControllerSections()
    {
        foreach ($this->bladeSections as $name => $section) {
            if (true === $this->sectionsProcessed && $this->sectionDataDiffers($name)) {
                $this->viewFactory->startSection($name, null);
                echo $section;
                $this->viewFactory->stopSection(true); // pass true to overwrite prior section content
            } elseif (false === $this->sectionsProcessed ||
              (true === $this->sectionsProcessed && !$this->sectionDataDiffers($name))
            ) {
                $this->viewFactory->startSection($name, $section);
            }
        }
        // On the frist run thru, mark things as processed & keep a copy of OG data.
        if (false === $this->sectionsProcessed) {
            $this->orgBladeSections = $this->bladeSections;
            $this->sectionsProcessed = true;
        }
    }

    /**
     * @param  string $sectionName
     * @return bool
     */
    private function sectionDataDiffers(string $sectionName): bool
    {
        if (false === isset($this->orgBladeSections[$sectionName])) {
            return false;
        }
        return $this->orgBladeSections[$sectionName] !== $this->bladeSections[$sectionName];
    }
}
