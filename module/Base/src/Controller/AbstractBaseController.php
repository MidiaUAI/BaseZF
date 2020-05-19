<?php

/**
 * @see       https://github.com/laminas/laminas-mvc-skeleton for the canonical source repository
 * @copyright https://github.com/laminas/laminas-mvc-skeleton/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-mvc-skeleton/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Base\Controller;

use Base\RequestTrait;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

abstract class AbstractBaseController extends AbstractActionController
{
    use RequestTrait;

    /**
     * The doctrine entity manager
     *
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $entityManager;

    /**
     * The service manager.
     *
     * @return \Laminas\ServiceManager\ServiceManager
     */
    protected function getServiceManager()
    {
        return $this->getEvent()->getApplication()->getServiceManager();
    }

    protected function getViewHelper($helperName)
    {
        return $this->getServiceManager()->get('ViewHelperManager')->get($helperName);
    }

    /**
     * Get doctrine entity manager.
     *
     * @return \Doctrine\ORM\EntityManagerInterface
     */
    protected function getEntityManager(): EntityManagerInterface
    {
        if (! isset($this->entityManager)) {
            $this->entityManager = $this->getServiceManager()->get('Doctrine\ORM\EntityManager');
        }
        return $this->entityManager;
    }

    /**
     * add a new file javascript on layout
     *
     * @param string $fileAdress
     * @return void
     * @example $this->addJsFile('adress-of-file.js');
     */
    protected function addJsFile($fileAdress)
    {
        $this->getViewHelper('HeadScript')->appendFile($fileAdress);
    }

    /**
     * Add a new css file on layout
     *
     * @param string $fileAdress
     *     The file address
     *
     * @return \Laminas\View\Helper\HeadLink
     *     The head link helper.
     *
     * @example $this->addCssFile('adress-of-file.css');
     */
    protected function addCssFile($fileAddress)
    {
        /** @var \Laminas\View\Helper\HeadLink */
        $headLink = $this->getViewHelper('HeadLink');
        $headLink->prependStylesheet($fileAddress);

        return $headLink;
    }

    protected function setHeadTitle($titlePage)
    {
        $this->getViewHelper('HeadTitle')->set($titlePage);
    }

    public function indexAction()
    {
        return [];
    }
}
