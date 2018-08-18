<?php

namespace App\Controller;

use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class TokenController extends AbstractController
{

    /**
     * for now we will just return a token to anyone who asks:
     * user authentication can be plugged in later
     * 
     * @Route("/token", name="new-token", methods={"POST"})
     */
    public function newToken(JWTEncoderInterface $jwtEncoder, UserProviderInterface $userProvider)
    {
        $token = $jwtEncoder
                    ->encode([
                        'username' => 'test_user',
                        'exp' => time() + 3600 // 1 hour expiration
                    ]);
        return new JsonResponse(
            [
                'token' => $token
            ],
            JsonResponse::HTTP_CREATED
        );
    }
}
