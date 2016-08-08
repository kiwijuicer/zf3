<?php
declare(strict_types=1);

namespace Application\Controller;

use Application\Authentication\Acl;
use Application\Authentication\AuthService;
use Application\Form\LoginForm;
use Core\Service\AdminUserService;
use Zend\Authentication\Result;

/**
 * AuthenticationController
 *
 * @package Admin\Controller
 * @author Norbert Hanauer <norbert.hanauer@check24.de>
 * @copyright CHECK24 Vergleichsportal GmbH
 */
class AuthenticationController extends AbstractController
{
    public function __construct(AuthService $authService, Acl $acl, AdminUserService $adminUserService)
    {
        parent::__construct($authService, $acl, $adminUserService);
    }

    /**
     * Login
     */
    public function loginAction()
    {
        $form = new LoginForm('login-form', [], $this->getRequest());

        $errorMessage = '';

        if ($this->getRequest()->isPost()) {

            $form->setData($this->getRequest()->getPost());

            if ($form->isValid()) {

                // try logging in

                $data = $form->getData();

                $result = $this->authService->authenticate($data['username'], $data['password'], $this->adminUserService);

                if ($result->isValid()) {

                    if (preg_match('#^/.*#', $data['olduri'])) {
                        return $this->redirect()->toUrl($data['olduri']);
                    } else {
                        return $this->redirect()->toRoute('home');
                    }

                } else {

                    $data['password'] = '';
                    $form->setData($data);

                    if ($result->getCode() == Result::FAILURE_CREDENTIAL_INVALID) {
                        $errorMessage = 'Benutzername und/oder Kennwort ungültig!';
                    } else {
                        $errorMessage = 'Unbekannter Fehler!';
                    }

                }

            }

        } else {

            $data = ['olduri' => $this->getRequest()->getQuery('olduri')];

            if ($this->authService->hasUser() && preg_match('#^/.*#', $data['olduri'])) {
                return $this->redirect()->toUrl($data['olduri']);
            }

            $form->setData($data);
        }

        $this->layout('layout/layout-no-login');
        return [
            'form' => $form,
            'errorMessage' => $errorMessage,
        ];
    }

    /**
     * Logout
     */
    public function logoutAction()
    {
        $this->authService->logout();

        return $this->redirect()->toRoute('admin-host/login');
    }
}
