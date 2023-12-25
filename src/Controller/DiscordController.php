<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\DiscordApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
class DiscordController extends AbstractController
{
    public function __construct(
        private readonly DiscordApiService $discordApiService
    )
    {
    }

    #[Route('/discord/connect', name: 'app_discord_connect')]
    public function connect(Request $request): Response
    {
        $token = $request->request->get('token');

        // if ($this->isCsrfTokenValid('discord-auth', $token)) {
            $request->getSession()->set('discord-auth', true);
            $scope = ['identify', 'email'];
            return $this->redirect($this->discordApiService->getAuthorizationUrl($scope));
        // }

        return $this->redirectToRoute('app_main');
    }

    #[Route('/discord/auth', name: 'app_discord_auth')]
    public function auth(): Response
    {
        return $this->redirectToRoute('app_main');
    }

    #[Route('/discord/check', name: 'app_discord_check')]
    public function check(EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher, Request $request, UserRepository $userRepo): Response
    {
        $accessToken = $request->get('access_token');

        if (!$accessToken) {
            return $this->render('discord/check.html.twig');
        }

        $discordUser = $this->discordApiService->fetchUser($accessToken);

        $user = $userRepo->findOneBy(['discordid' => $discordUser->id]);

        if ($user) {
            return $this->redirectToRoute('app_discord_auth', [
                'accessToken' => $accessToken
            ]);
        }

        $user = new User();

        $avatarUrl = "https://cdn.discordapp.com/avatars/{$discordUser->id}/{$discordUser->avatar}.webp";

        $user->setAccessToken($accessToken);
        $user->setUsername($discordUser->username);
        $user->setEmail($discordUser->email);
        $user->setAvatar($avatarUrl);
        $user->setDiscordId($discordUser->id);

        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                md5(uniqid())
            )
        );


        // Get the current date & time (in current timezone immutable)
        $currentDateTime = new \DateTimeImmutable();
        // Set the user's registration date & time
        $user->setCreatedAt($currentDateTime);
        $user->setUpdatedAt($currentDateTime);

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('app_discord_auth', [
            'accessToken' => $accessToken
        ]);
    }
}