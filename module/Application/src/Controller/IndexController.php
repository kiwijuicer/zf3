<?php
declare(strict_types=1);

namespace Application\Controller;

use Application\Authentication\Acl;
use Application\Authentication\AuthService;
use Zend\View\Model\ViewModel;

/**
 * Index Controller
 *
 * @package Application\Controller
 */
class IndexController extends AbstractController
{
    /**
     * Constructor for Index Controller
     *
     * @param \Application\Authentication\AuthService $authService
     * @param \Application\Authentication\Acl $acl
     */
    public function __construct(AuthService $authService, Acl $acl)
    {
        parent::__construct($authService, $acl);
    }

    /**
     * Outputs index view
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        return new ViewModel();
    }
}
