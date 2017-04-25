<?php
declare(strict_types=1);

namespace Application\Controller;

use Application\Authentication\Acl;
use Application\Authentication\AuthService;
use Application\Form\LoginForm;
use Core\Entity\AdminUser;
use Core\Service\AdminUserService;
use Facebook\Facebook;
use Zend\Authentication\Result;
use Zend\Config\Config;

/**
 * AuthenticationController
 *
 * @package Admin\Controller
 * @copyright CHECK24 Vergleichsportal GmbH
 */
class AuthenticationController extends AbstractController
{
    /**
     * Admin user service
     *
     * @var \Core\Service\AdminUserService
     */
    protected $adminUserService;

    /**
     * Config
     *
     * @var \Zend\Config\Config
     */
    protected $config;

    /**
     * Constructor for Authentication Controller
     *
     * @param \Application\Authentication\AuthService $authService
     * @param \Application\Authentication\Acl $acl
     * @param \Core\Service\AdminUserService $adminUserService
     * @param \Zend\Config\Config $config
     */
    public function __construct(AuthService $authService, Acl $acl, AdminUserService $adminUserService, Config $config)
    {
        parent::__construct($authService, $acl);
        $this->adminUserService = $adminUserService;
        $this->config = $config;
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
     * Facebook Login
     *
     * @return \Zend\Http\Response
     */
    public function facebookLoginAction()
    {
        $facebook = new Facebook([
            'app_id' => $this->config->facebook->app_id,
            'app_secret' => $this->config->facebook->app_secret
        ]);

        if ($code = $this->params()->fromQuery('code', null)) {

            $accessToken = $facebook->getOAuth2Client()->getAccessTokenFromCode($code, 'http://dev.norbert.nh/facebook-login');

            $response = $facebook->get('/me?fields=id,first_name,last_name,email,picture', $accessToken->getValue());

            if (null === ($user = $this->adminUserService->getByFbId((int)$response->getGraphUser()->getId()))) {

                $user = new AdminUser();
                $user->setFbId((int)$response->getGraphUser()->getId());
                $user->setEmail($response->getGraphUser()->getEmail());
                $user->setUsername(strtolower($response->getGraphUser()->getFirstName()) . '.' . strtolower($response->getGraphUser()->getLastName()));
                $user->setPassword($response->getGraphUser()->getId());
                $user->setRole(AdminUser::ROLE_USER);
                $user->setStatus(AdminUser::STATUS_ENABLED);
            }

            $user->setFbIsActive(true);
            $user->setFbToken($response->getAccessToken());
            $user->setFbPictureUrl($response->getGraphUser()->getPicture()->getUrl());
            $user->setFirstName($response->getGraphUser()->getFirstName());
            $user->setLastName($response->getGraphUser()->getLastName());

            $this->adminUserService->save($user);

            $result = $this->authService->authenticate($user->getUsername(), $response->getGraphUser()->getId(), $this->adminUserService);

            if ($result->isValid()) {
                return $this->redirect()->toRoute('home');
            }
        }

        return $this->redirect()->toRoute('login');
    }

    /**
     * Facebook Remove
     *
     * @return \Zend\Http\Response
     */
    public function facebookRemoveAction()
    {
        if (null === ($signedRequest = $this->params()->fromQuery('signed_request', null))) {
            return json_encode(['success' => false]);
        }

        $result = $this->parseSignedRequest($signedRequest);

        $user = $this->adminUserService->getByFbId((int)$result['user_id']);

        if ($user instanceof AdminUser) {

            $user->setFbIsActive(false);
            $user->setStatus(AdminUser::STATUS_DISABLED);

            $this->adminUserService->save($user);
        }

        return json_encode(['success' => true]);
    }

    /**
     * Logout
     */
    public function logoutAction()
    {
        $this->authService->logout();

        return $this->redirect()->toRoute('login');
    }

    /**
     * Returns parsed signed request
     *
     * @param $signedRequest
     * @return mixed|null
     */
    protected function parseSignedRequest($signedRequest)
    {

        list($encodedSig, $payload) = explode('.', $signedRequest, 2);

        $sig = $this->base64UrlDecode($encodedSig);
        $data = json_decode($this->base64UrlDecode($payload), true);

        // confirm the signature
        $expectedSig = hash_hmac('sha256', $payload, $this->config->facebook->app_secret, true);

        if ($sig !== $expectedSig) {
            return $this->redirect()->toRoute('login');
        }

        return $data;
    }

    /**
     * Returns the base64 decoded value
     *
     * @param string $input
     * @return string
     */
    protected function base64UrlDecode(string $input) {
        return base64_decode(strtr($input, '-_', '+/'));
    }
}
