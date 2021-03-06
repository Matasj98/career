<?php

namespace App\Controller;

use App\Factory\ListViewFactory;
use App\Factory\ProfileViewFactory;
use App\Request\CareerProfileRequest;
use App\Service\CareerProfileService;
use FOS\RestBundle\View\ViewHandlerInterface;
use App\Repository\CareerProfileRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CareerProfileController
 *
 * endpoints:
 * /api/profiles/{slug} - get career profile by profession;
 * /api/profile/list - get all career profiles;
 * /api/profiles - post new career profile
 *
 * @package App\Controller
 */
class CareerProfileController extends AbstractFOSRestController
{
    /** @var CareerProfileRepository */
    private $careerProfileRepository;

    /** @var ViewHandlerInterface */
    private $viewHandler;

    /** @var ProfileViewFactory */
    private $profileViewFactory;

    /** @var  CareerProfileService */
    private $careerProfileService;

    /** @var ListViewFactory */
    private $listViewFactory;

    /**
     * CareerProfileController constructor.
     * @param ViewHandlerInterface $viewHandler
     * @param CareerProfileRepository $careerProfileRepository
     * @param ProfileViewFactory $profileViewFactory
     * @param CareerProfileService $careerProfileService
     * @param ListViewFactory $listViewFactory
     */
    public function __construct(
        ViewHandlerInterface $viewHandler,
        CareerProfileRepository $careerProfileRepository,
        ProfileViewFactory $profileViewFactory,
        CareerProfileService $careerProfileService,
        ListViewFactory $listViewFactory
    ) {
        $this->viewHandler = $viewHandler;
        $this->profileViewFactory = $profileViewFactory;
        $this->careerProfileRepository = $careerProfileRepository;
        $this->careerProfileService = $careerProfileService;
        $this->listViewFactory = $listViewFactory;
    }

    /**
     * Post new CareerProfile
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function postProfileAction(Request $request)
    {
        $requestObject = new CareerProfileRequest($request);
        if (!$this->careerProfileService->handleSave($requestObject)) {
            return new Response(Response::HTTP_NOT_FOUND);
        }

        return new Response(json_encode(['message' => 'Created']), Response::HTTP_CREATED);
    }

    /**
     * Get a list of career profiles
     * @return Response
     */
    public function getProfileListAction()
    {
        $profileList = $this->careerProfileRepository->findBy(['isArchived' => 0]);
        $this->listViewFactory->setViewFactory(ProfileViewFactory::class);
        return $this->viewHandler->handle(View::create($this->listViewFactory->create($profileList)));
    }

    /**
     * Get CareerProfile by occupation/profession
     * @param string $slug
     * @return Response
     */
    public function getProfileAction($slug)
    {
        $careerProfile = $this->careerProfileRepository->findOneBy(['profession' => $slug, 'isArchived' => 0]);
        if (!$careerProfile) {
            return new Response(Response::HTTP_NOT_FOUND);
        }
        return $this->viewHandler->handle(View::create($this->profileViewFactory->create($careerProfile)));
    }
}
