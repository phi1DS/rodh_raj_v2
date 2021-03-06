<?php

namespace App\Services\Binder;

use App\Entity\RoomAction\ChanceAction;
use App\Entity\RoomAction\Choice;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;

class TwigChoiceBinder
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function bindChoices(Collection $currentChoices, User $user): array
    {
        //Adapte les choice pour un rendu twig
        $resultChoiceArray = [];
        $itemChoiceArray = [];
        /** @var Choice $choice */
        foreach ($currentChoices as $choice) {
            //Si une chance est associée à la réussite de l'action
            if (!empty($choice->getChanceAction())) {
                /** @var ChanceAction $chanceAction */
                $chanceAction = $choice->getChanceAction();
                $successChance = $chanceAction->getChance();

                if ($successChance >= rand(1, 10)) { //Success
                    $resultChoiceArray[] = ['resultRoomAction' => $chanceAction->getSuccessRoomAction(), 'text' => $choice->getText()];
                } else { // Failure
                    $resultChoiceArray[] = ['resultRoomAction' => $chanceAction->getFailRoomAction(), 'text' => $choice->getText()];
                }
            }

            //test si le joueur à des items liés aux choice si oui, display le choice
            if (!empty($choice->getItemAction()) && $user->getItems()->contains($choice->getItemAction())) {
                $itemChoiceArray[] = ['hasItem' => true, 'resultRoomAction' => $choice->getTargetRoomAction(), 'text' => $choice->getText()];

                $user->removeItem($choice->getItemAction());
                $this->userRepository->add($user);
            }
        }

        return [$resultChoiceArray, $itemChoiceArray];
    }
}
