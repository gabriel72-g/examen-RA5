<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Controlador de seguridad para manejo de login y logout.
 *
 * Proporciona las rutas de inicio de sesión y cierre de sesión.
 */
class SecurityController extends AbstractController
{
    /**
     * Página de login.
     *
     * Esta acción muestra el formulario de login y gestiona posibles errores
     * de autenticación.
     *
     * @param AuthenticationUtils $authenticationUtils Servicio para manejar errores y último usuario
     * @return Response Devuelve la plantilla del formulario de login
     */
    #[Route(path: '/', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * Logout del usuario.
     *
     * Symfony intercepta esta ruta a través del firewall, por lo que
     * este método no debe contener lógica.
     *
     * @throws \LogicException Siempre lanza una excepción para indicar
     *                           que Symfony maneja el logout
     */
    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('Interceptado por Symfony firewall.');
    }
}
