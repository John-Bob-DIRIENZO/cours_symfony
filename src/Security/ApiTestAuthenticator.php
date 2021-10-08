<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class ApiTestAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('MON_TOKEN');
    }

    public function authenticate(Request $request): PassportInterface
    {
        $userId = $request->headers->get('MON_TOKEN');

        return new SelfValidatingPassport(
            // La valeur de $userId est passée au callback
            new UserBadge($userId, function (string $userId) {
                // Et le callback est chargé d'aller chercher l'User
                return $this->userRepository->findOneBy([
                    'id' => $userId
                ]);
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new JsonResponse([
            'message' => 'Vous devez être authentifié'],
            Response::HTTP_UNAUTHORIZED
        );
    }



//    public function createAuthenticatedToken(PassportInterface $passport, string $firewallName): TokenInterface
//    {
//        if (!$passport instanceof UserPassportInterface) {
//            throw new LogicException(sprintf('Passport does not contain a user, overwrite "createAuthenticatedToken()" in "%s" to create a custom authenticated token.', static::class));
//        }
//
//        return new AnonymousToken('mon_secret', $passport->getUser(), $passport->getUser()->getRoles());
//    }
}
