<?php


namespace App\Service;

use App\Entity\CareerProfile;
use App\Entity\Profession;
use App\Repository\CareerProfileRepository;
use App\Repository\CriteriaRepository;
use App\Repository\ProfessionRepository;
use App\Request\CareerProfileRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CareerProfileService
{
    /** @var CareerProfileRepository  */
    private $careerProfileRepository;

    /** @var CriteriaRepository */
    private $criteriaRepository;

    /** @var ProfessionRepository */
    private $professionRepository;

    public function __construct(
        CareerProfileRepository $careerProfileRepository,
        CriteriaRepository $criteriaRepository,
        ProfessionRepository $professionRepository
    ) {
        $this->criteriaRepository = $criteriaRepository;
        $this->professionRepository = $professionRepository;
        $this->careerProfileRepository = $careerProfileRepository;
    }

    /**
     * @param CareerProfileRequest $request
     * @return bool
     */
    public function handleCareerProfileSave(CareerProfileRequest $request)
    {
        $profession = $this->professionRepository->findOneBy(['id' => $request->getProfessionId()]);

        if (!$profession) {
            return false;
        }

        $criteriaList = $this->criteriaRepository->findBy(array('id' => $request->getCriteriaIds()));
        if (!$criteriaList) {
            return false;
        }

        $this->saveCareerProfile($criteriaList, $profession);

        return true;
    }


    /**
     * @param array $criteriaList
     * @param Profession $profession
     */
    public function saveCareerProfile(Array $criteriaList, Profession $profession)
    {
        $careerProfile = ($this->careerProfileRepository->findOneBy(['profession' => $profession])) ??
            new CareerProfile();

        if ($criteriaList != null) {
            foreach ($criteriaList as $criteria) {
                $careerProfile->addFkCriterion($criteria);
            }
        }

        $careerProfile->setProfession($profession);
        $careerProfile->setIsArchived(0);

        $this->careerProfileRepository->save($careerProfile);
    }
}
