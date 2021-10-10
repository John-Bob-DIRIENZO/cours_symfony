<?php

namespace App\Security;

use App\Repository\ApiTokenRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiTokenAuthenticator extends AbstractAuthenticator
{

    private ApiTokenRepository $tokenRepository;
    private string $appSecret;

    public function __construct(ApiTokenRepository $tokenRepository, string $appSecret)
    {
        $this->tokenRepository = $tokenRepository;
        $this->appSecret = $appSecret;
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('authorization')
            && strpos($request->headers->get('authorization'), 'Bearer ') === 0;
    }

    public function authenticate(Request $request): PassportInterface
    {
        $token = str_replace('Bearer ', '', $request->headers->get('authorization'));
        return new SelfValidatingPassport(new UserBadge($token, function ($token) {
            $tokenObj = $this->tokenRepository->findOneBy(['token' => $token]);
            if (!$tokenObj) {
                throw new CustomUserMessageAuthenticationException('Invalid Token');
            }
            if ($tokenObj->isExpired()) {
                throw new CustomUserMessageAuthenticationException('Expired Token');
            }
            return $tokenObj->getUser();
        }));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse([
            'message' => $exception->getMessage()
        ], 401);
    }

    public function createAuthenticatedToken(PassportInterface $passport, string $firewallName): TokenInterface
    {
        return new AnonymousToken($this->appSecret, $passport->getUser(), $passport->getUser()->getRoles());
    }



//    public function start(Request $request, AuthenticationException $authException = null): Response
//    {
//        /*
//         * If you would like this class to control what happens when an anonymous user accesses a
//         * protected page (e.g. redirect to /login), uncomment this method and make this class
//         * implement Symfony\Component\Security\Http\EntryPoint\AuthenticationEntrypointInterface.
//         *
//         * For more details, see https://symfony.com/doc/current/security/experimental_authenticators.html#configuring-the-authentication-entry-point
//         */
//    }
}
