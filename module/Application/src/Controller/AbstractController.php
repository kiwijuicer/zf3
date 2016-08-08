<?php
declare(strict_types=1);

namespace Application\Controller;

use Application\Authentication\Acl;
use Application\Authentication\AuthService;
use Core\Service\AdminUserService;
use Zend\Authentication\Adapter\Http;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

class AbstractController extends AbstractActionController
{
    protected $authService;
    protected $acl;
    protected $adminUserService;

    public function __construct(AuthService $authService, Acl $acl, AdminUserService $adminUserService)
    {
        $this->authService = $authService;
        $this->acl = $acl;
        $this->adminUserService = $adminUserService;
    }

    /**
     * Execute the request
     *
     * @param  MvcEvent $e
     * @return mixed
     * @throws \DomainException
     */
    public function onDispatch(MvcEvent $e)
    {
        $match = $e->getRouteMatch();
        $auth = $match->getParam('auth', true);

        if ($auth === true) {

            if (!$this->authService->hasUser())  {
                return $this->redirectToLogin();
            } else {

                $controller = $match->getParam('controller');
                $action = $match->getParam('action');

                if (!$this->acl->hasResource($controller) || !$this->acl->isAllowed($this->authService->getSchema(), $controller, $action)) {

                    $action_response = $this->noAccessAction();
                    $e->setResult($action_response);

                    return $action_response;
                }

            }

        }

        return parent::onDispatch($e);
    }

    /**
     * Redirects to login page
     *
     * @return Ambigous <\Zend\Http\Response, \Zend\Stdlib\ResponseInterface>
     */
    public function redirectToLogin()
    {
        $options = [];

        if ('/' !== $_SERVER['REQUEST_URI']) {
            $options['query'] = ['olduri' => $_SERVER['REQUEST_URI']];
        }

        return $this->redirect()->toRoute('login', [], $options);
    }
}
