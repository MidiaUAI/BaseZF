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
use Laminas\View\Helper\HeadLink;
use Laminas\View\Helper\HeadScript;
use Laminas\View\Helper\HeadTitle;
use Laminas\View\Helper\HelperInterface;
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

    /**
     * Get a view helper
     *
     * @param string $helperName
     *      The helper name.
     * @return \Laminas\View\Helper\HelperInterface
     */
    protected function getViewHelper($helperName): HelperInterface
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
     * Add a new javascript file on layout.
     *
     * @param string $fileAdress
     *      The file address.
     *
     * @return \Laminas\View\Helper\HeadScript
     *      The head script helper.
     *
     * @example $this->addJsFile('adress-of-file.js');
     */
    protected function addJsFile($fileAdress): HeadScript
    {
        /** @var \Laminas\View\Helper\HeadScript $headScript */
        $headScript = $this->getViewHelper('HeadScript');
        $headScript->appendFile($fileAdress);

        return $headScript;
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
    protected function addCssFile($fileAddress): HeadLink
    {
        /** @var \Laminas\View\Helper\HeadLink */
        $headLink = $this->getViewHelper('HeadLink');
        $headLink->prependStylesheet($fileAddress);

        return $headLink;
    }

    /**
     * Set title page
     *
     * @param string $titlePage
     *      The page title
     *
     * @return \Laminas\View\Helper\HeadTitle
     */
    protected function setHeadTitle($titlePage): HeadTitle
    {
        /** @var \Laminas\View\Helper\HeadTitle */
        $headTitle = $this->getViewHelper('HeadTitle');
        $headTitle->set($titlePage);

        return $headTitle;
    }

    public function indexAction()
    {
        dd(
            $this->getServiceManager(),
            $this->getServiceManager()->get('RouteListener')
        );
        $this->setHeadTitle('isso');
        return new ViewModel();
    }
}
