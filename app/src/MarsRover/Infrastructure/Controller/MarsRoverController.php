<?php

declare(strict_types=1);

namespace App\MarsRover\Infrastructure\Controller;

use App\MarsRover\Application\Command\CreateMarsRover;
use App\MarsRover\Application\Command\MoveBackwardMarsRover;
use App\MarsRover\Application\Command\MoveForwardMarsRover;
use App\MarsRover\Application\Command\TurnLeftMarsRover;
use App\MarsRover\Application\Command\TurnRightMarsRover;
use App\MarsRover\Domain\MarsRoverId;
use App\MarsRover\Domain\Orientation;
use App\MarsRover\Domain\Position;
use App\MarsRover\Infrastructure\ReadModel\DBALRepository;
use App\MarsRover\Model\ReadModel\Rover;
use Broadway\CommandHandling\CommandBus;
use Broadway\UuidGenerator\UuidGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MarsRoverController extends AbstractController
{
    private CommandBus $commandBus;
    private UuidGeneratorInterface $uuidGenerator;
    private DBALRepository $repository;

    public function __construct(
        CommandBus $commandBus,
        UuidGeneratorInterface $uuidGenerator,
        DBALRepository $repository
    ) {
        $this->commandBus = $commandBus;
        $this->uuidGenerator = $uuidGenerator;
        $this->repository = $repository;
    }

    /**
     * @Route("/", methods={"GET"})
     */
    public function index(): Response
    {
        $marsRoversData = $this->repository->findAll();
        $data = array_map(fn (Rover $marsRover) => $marsRover->toArray(), $marsRoversData);

        return $this->render('index.html.twig', [
            'mars_rovers' => $data,
        ]);
    }

    /**
     * @Route("/detail/{id}", methods={"GET"})
     */
    public function detail(string $id): Response
    {
        $marsRover = $this->repository->find($id);

        return $this->render('detail.html.twig', ['mars_rover' => $marsRover->toArray()]);
    }

    /**
     * @Route("/create", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $id = new MarsRoverId($this->uuidGenerator->generate());

        $command = new CreateMarsRover(
            $id,
            $request->get('name'),
            Position::create(
                (int) $request->get('position_x'),
                (int) $request->get('position_y')
            ),
            Orientation::fromString($request->get('orientation'))
        );

        $this->commandBus->dispatch($command);

        return new JsonResponse(['id' => (string) $id]);
    }

    /**
     * @Route("/detail/{id}/move_forward", methods={"POST"})
     */
    public function moveForward(string $id): Response
    {
        $command = new MoveForwardMarsRover(new MarsRoverId($id));
        $this->commandBus->dispatch($command);

        return new JsonResponse(['id' => $id, 'action' => 'moveForward']);
    }

    /**
     * @Route("/detail/{id}/move_backward", methods={"POST"})
     */
    public function moveBackward(string $id): Response
    {
        $command = new MoveBackwardMarsRover(new MarsRoverId($id));
        $this->commandBus->dispatch($command);

        return new JsonResponse(['id' => $id, 'action' => 'moveBackward']);
    }

    /**
     * @Route("/detail/{id}/turn_left", methods={"POST"})
     */
    public function turnLeft(string $id): Response
    {
        $command = new TurnLeftMarsRover(new MarsRoverId($id));
        $this->commandBus->dispatch($command);

        return new JsonResponse(['id' => $id, 'action' => 'turnLeft']);
    }

    /**
     * @Route("/detail/{id}/turn_right", methods={"POST"})
     */
    public function turnRight(string $id): Response
    {
        $command = new TurnRightMarsRover(new MarsRoverId($id));
        $this->commandBus->dispatch($command);

        return new JsonResponse(['id' => $id, 'action' => 'turnRight']);
    }
}
