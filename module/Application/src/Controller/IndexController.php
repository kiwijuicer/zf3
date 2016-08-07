<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Authentication\AuthService;
use Core\Service\AdminUserService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractController
{
    public function __construct(AuthService $authService, AdminUserService $adminUserService)
    {
        parent::__construct($authService, $adminUserService);
    }

    public function indexAction()
    {
        return new ViewModel();
    }
}
