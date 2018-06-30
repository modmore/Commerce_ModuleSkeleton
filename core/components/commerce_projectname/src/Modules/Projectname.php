<?php
namespace ThirdParty\Projectname\Modules;
use modmore\Commerce\Modules\BaseModule;
use modmore\Commerce\Admin\Widgets\Form\DescriptionField;
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

        // Add template path to twig
//        /** @var ChainLoader $loader */
//        $root = dirname(dirname(__DIR__));
//        $loader = $this->commerce->twig->getLoader();
//        $loader->addLoader(new FilesystemLoader($root . '/templates/'));
    }

    public function getModuleConfiguration(\comModule $module)
    {
        $fields = [];

//        $fields[] = new DescriptionField($this->commerce, [
//            'description' => $this->adapter->lexicon('commerce_projectname.module_description'),
//        ]);

        return $fields;
    }
}
