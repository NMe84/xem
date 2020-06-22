<?php

/*
 * The Xross Entity Map
 * https://github.com/NMe84/xem
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CredentialsExpiredException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class FormLoginAuthenticator extends AbstractFormLoginAuthenticator
{
    protected RouterInterface $router;
    protected TokenStorageInterface $tokenStorage;
    protected UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(RouterInterface $router, TokenStorageInterface $tokenStorage, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * {@inheritdoc}
     */
    protected function getLoginUrl()
    {
        return $this->router->generate('login');
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials(Request $request)
    {
        $username = $request->request->get('_username');
        $request->getSession()->set(Security::LAST_USERNAME, $username);
        $password = $request->request->get('_password');

        return [
            'username' => $username,
            'password' => $password,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials['username'];

        $user = $userProvider->loadUserByUsername($username);
        if (!$user->getUsername()) {
            $userProvider->refreshUser($user);
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        $plainPassword = $credentials['password'];

        if (!$this->passwordEncoder->isPasswordValid($user, $plainPassword)) {
            throw new BadCredentialsException();
        }
        if ($user instanceof User) {
            if ($user->isDeleted() || !$user->isActive()) {
                throw new CredentialsExpiredException();
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultSuccessRedirectUrl()
    {
        return $this->router->generate('profile');
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $targetPath = null;

        if ($request->getSession() instanceof SessionInterface) {
            $targetPath = $request->getSession()->get('_security.' . $providerKey . '.target_path');
        }

        if (!$targetPath) {
            $targetPath = $this->getDefaultSuccessRedirectUrl();
        }

        return new RedirectResponse($targetPath);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(Request $request)
    {
        return $request->isMethod('POST') && 'login' === $request->attributes->get('_route');
    }
}
