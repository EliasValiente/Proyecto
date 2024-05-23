<?php
namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

define('ROLES', array('ROLE_USER'));

class ApiRegistrationController extends AbstractController
{
    private $entityManager;
    private $passwordHasher;
    private $validator;
    private $tokenStorage;
    private $eventDispatcher;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher,
        ValidatorInterface $validator,
        TokenStorageInterface $tokenStorage,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $userPasswordHasher;
        $this->validator = $validator;
        $this->tokenStorage = $tokenStorage;
        $this->eventDispatcher = $eventDispatcher;
    }

    #[Route('/api/registration', name: 'app_registration', methods:['POST'])]
    public function register(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        // Validar datos
        $errors = $this->validateRegistrationData($data);
        if (!empty($errors)) {
            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        // Crear el nuevo usuario
        $user = new User();
        $user->setRoles([ROLES[0]]);
        $user->setNombre($data['nombre']);
        $user->setApellidos($data['apellido']);
        $user->setUserName($data['usuario']);
        $user->setEmail($data['email']);
        $user->setTarjeta($data['tarjeta']);
        $user->setFechaValidez(new \DateTime($data['fechaValidez']));

        // Codificar la contraseña
        $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));

        // Codificar el cvv
        $user->setCvv($this->passwordHasher->hashPassword($user, $data['cvv']));

        // Validar entidad User
        $validationErrors = $this->validator->validate($user);
        if (count($validationErrors) > 0) {
            return $this->json(['errors' => (string) $validationErrors], Response::HTTP_BAD_REQUEST);
        }

        // Guardar el usuario en la base de datos
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Autenticar al usuario manualmente
        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);

        // Disparar el evento de login interactivo
        $event = new InteractiveLoginEvent($request, $token);
        $this->eventDispatcher->dispatch($event, SecurityEvents::INTERACTIVE_LOGIN);

        return new JsonResponse('Usuario registrado correctamente', Response::HTTP_ACCEPTED);
    }

    private function validateRegistrationData(array $data): array
    {
        $errors = [];

        if (empty($data['nombre'])) {
            $errors[] = 'El nombre es requerido';
        }
        if (empty($data['apellido'])) {
            $errors[] = 'El apellido es requerido';
        }
        if (empty($data['usuario'])) {
            $errors[] = 'El usuario es requerido';
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'El email es requerido y debe ser un email válido';
        }
        if (empty($data['tarjeta']) || !preg_match('/^[0-9]{16}$/', $data['tarjeta'])) {
            $errors[] = 'La tarjeta es requerida y debe ser un número de 16 dígitos';
        }
        if (empty($data['cvv']) || !preg_match('/^[0-9]{3,4}$/', $data['cvv'])) {
            $errors[] = 'El CVV es requerido y debe ser un número de 3 o 4 dígitos';
        }
        if (empty($data['fechaValidez'])) {
            $errors[] = 'La fecha de validez es requerida';
        }
        if (empty($data['password']) || strlen($data['password']) < 6) {
            $errors[] = 'La contraseña es requerida y debe tener al menos 6 caracteres';
        }
        if (empty($data['repetirPassword']) || $data['password'] !== $data['repetirPassword']) {
            $errors[] = 'Las contraseñas no coinciden';
        }

        return $errors;
    }
}
