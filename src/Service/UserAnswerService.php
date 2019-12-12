<?php


namespace App\Service;

use App\Entity\CareerForm;
use App\Entity\UserAnswer;
use App\Repository\CareerFormRepository;
use App\Repository\CriteriaChoiceRepository;
use App\Repository\CriteriaRepository;
use App\Repository\UserAnswerRepository;
use App\Request\UserAnswerRequest;
use Exception;

class UserAnswerService
{

    /** @var UserAnswerRepository */
    private $userAnswerRepository;

    /** @var CareerFormRepository */
    private $careerFormRepository;

    /** @var CriteriaRepository */
    private $criteriaRepository;

    /** @var CriteriaChoiceRepository */
    private $criteriaChoiceRepository;

    public function __construct(
        CriteriaRepository $criteriaRepository,
        UserAnswerRepository $userAnswerRepository,
        CareerFormRepository $careerFormRepository,
        CriteriaChoiceRepository $criteriaChoiceRepository
    ) {
        $this->criteriaRepository = $criteriaRepository;
        $this->userAnswerRepository = $userAnswerRepository;
        $this->careerFormRepository = $careerFormRepository;
        $this->criteriaChoiceRepository = $criteriaChoiceRepository;
    }

    /**
     * @param UserAnswerRequest $request
     * @return bool
     * @throws Exception
     */
    public function handleSaveUserAnswers(UserAnswerRequest $request)
    {
        $form = $this->careerFormRepository->findOneBy(['id' => $request->getFormId()]);

        if (!$form) {
            return false;
        }

        if ($request->getChoiceIds()) {
            $choices = $this->criteriaChoiceRepository->findBy([
                'id' => $request->getChoiceIds()]);
            $this->addChoicesToForm($choices, $form);
        }

        if ($request->getComments()) {
            $this->addCommentsToForm($request->getComments(), $form);
        }

        return true;
    }

    /**
     * @param array $choices
     * @param CareerForm $form
     * @return void
     * @throws Exception
     */
    private function addChoicesToForm(Array $choices, CareerForm $form)
    {
        foreach ($choices as $choice) {
            $answered = $this->userAnswerRepository->findOneBy([
                'fkCareerForm' => $form,
                'fkCriteria' => $choice->getFkCriteria()]);

            $userAnswer = $answered ?? new UserAnswer();

            if (!$userAnswer->getId()) {
                $userAnswer->setCreatedAt(new \DateTime("now"));
            } else {
                $userAnswer->setUpdatedAt(new \DateTime("now"));
            }

            $userAnswer->setFkChoice($choice);
            $userAnswer->setFkCriteria($choice->getFkCriteria());
            $userAnswer->setFkCareerForm($form);

            $this->userAnswerRepository->save($userAnswer);
            $form->addUserAnswer($userAnswer);
        }

        $form->setUpdatedAt(new \DateTime("now"));
        $this->careerFormRepository->save($form);
    }

    /**
     * @param array $comments
     * @param CareerForm $form
     * @return void
     * @throws Exception
     */
    private function addCommentsToForm(Array $comments, CareerForm $form)
    {
        foreach ($comments as $key => $comment) {
            $criteriaId = (int)$comment['criteriaId'] ?? null;
            $criteria = $this->criteriaRepository->findOneBy(['id' => $criteriaId]);
            $commentBody = (string)$comment['comment'] ?? null;
            $answered = $this->userAnswerRepository->findOneBy([
                'fkCriteria' => $criteriaId,
                'fkCareerForm' => $form]);

            $userAnswer = ($answered) ?? new UserAnswer();

            if (!$userAnswer->getId()) {
                $userAnswer->setCreatedAt(new \DateTime('now'));
            } else {
                $userAnswer->setUpdatedAt(new \DateTime('now'));
            }
            $userAnswer->setComment($commentBody);
            $userAnswer->setFkCriteria($criteria);
            $this->userAnswerRepository->save($userAnswer);
            $form->addUserAnswer($userAnswer);
        }
        $form->setUpdatedAt(new \DateTime("now"));
        $this->careerFormRepository->save($form);
    }
}
