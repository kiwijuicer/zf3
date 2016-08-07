<?php
declare(strict_types=1);

namespace Application\Controller;

use Application\Authentication\Acl;
use Application\Authentication\AuthService;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

/**
 * Abstract Controller
 *
 * @package Application\Controller
 */
class AbstractController extends AbstractActionController
{
    /**
     * Authentication Service
     *
     * @var \Application\Authentication\AuthService
     */
    protected $authService;

    /**
     * Acl
     *
     * @var \Application\Authentication\Acl
     */
    protected $acl;

    /**
     * Constructor for Abstract Controller
     *
     * @param \Application\Authentication\AuthService $authService
     * @param \Application\Authentication\Acl $acl
     */
    public function __construct(AuthService $authService, Acl $acl)
    {
        $this->authService = $authService;
        $this->acl = $acl;
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
     * @return \Zend\Http\Response
     */
    public function redirectToLogin() : Response
    {
        $options = [];

        if ('/' !== $_SERVER['REQUEST_URI']) {
            $options['query'] = ['olduri' => $_SERVER['REQUEST_URI']];
        }

        return $this->redirect()->toRoute('login', [], $options);
    }
}
