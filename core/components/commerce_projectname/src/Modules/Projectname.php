<?php
namespace ThirdParty\Projectname\Modules;
use modmore\Commerce\Admin\Configuration\About\ComposerPackages;
use modmore\Commerce\Admin\Sections\SimpleSection;
use modmore\Commerce\Admin\Widgets\Form\DescriptionField;
use modmore\Commerce\Events\Admin\PageEvent;
use modmore\Commerce\Modules\BaseModule;
use Symfony\Component\EventDispatcher\EventDispatcher;

require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';

class Projectname extends BaseModule {

    public function getName()
    {
        $this->adapter->loadLexicon('commerce_projectname:default');
        return $this->adapter->lexicon('commerce_projectname');
    }

    public function getAuthor()
    {
        return 'Your Name Here';
    }

    public function getDescription()
    {
        return $this->adapter->lexicon('commerce_projectname.description');
    }

    public function initialize(EventDispatcher $dispatcher)
    {
        // Load our lexicon
        $this->adapter->loadLexicon('commerce_projectname:default');

        // Add the xPDO package, so Commerce can detect the derivative classes
//        $root = dirname(dirname(__DIR__));
//        $path = $root . '/model/';
//        $this->adapter->loadPackage('commerce_projectname', $path);

        // Add template path to twig - pre-1.1 way
//        /** @var ChainLoader $loader */
//        $root = dirname(dirname(__DIR__));
//        $loader = $this->commerce->twig->getLoader();
//        $loader->addLoader(new FilesystemLoader($root . '/templates/'));
        // Add template path to twig - 1.1+ way
//        $this->commerce->view()->addTemplatesPath($root . '/templates/');

        // Added in 0.12.0, to add composer libraries to the about section
        if (defined('\Commerce::EVENT_DASHBOARD_LOAD_ABOUT')) {
            $dispatcher->addListener(\Commerce::EVENT_DASHBOARD_LOAD_ABOUT, [$this, 'addLibrariesToAbout']);
        }
    }

    public function getModuleConfiguration(\comModule $module)
    {
        $fields = [];

        $fields[] = new DescriptionField($this->commerce, [
            'description' => $this->adapter->lexicon('commerce_projectname.module_description'),
        ]);

        return $fields;
    }

    public function addLibrariesToAbout(PageEvent $event)
    {
        $lockFile = dirname(dirname(__DIR__)) . '/composer.lock';
        if (file_exists($lockFile)) {
            $section = new SimpleSection($this->commerce);
            $section->addWidget(new ComposerPackages($this->commerce, [
                'lockFile' => $lockFile,
                'heading' => $this->adapter->lexicon('commerce.about.open_source_libraries') . ' - ' . $this->adapter->lexicon('commerce_projectname'),
                'introduction' => '', // Could add information about how libraries are used, if you'd like
            ]));

            $about = $event->getPage();
            $about->addSection($section);
        }
    }
}
