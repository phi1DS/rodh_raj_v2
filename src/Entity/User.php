<?php
declare(strict_types=1);

namespace App\Entity;

use App\Entity\RoomAction\Binder;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Serializable;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface, Serializable, ResourceInterface
{
    const LIFE_FULL = 3;
    const LIFE_EMPTY = 0;
    const START_ROOM_NUMBER = 1;

    /** @var int */
    private $id;
    /** @var string */
    private $name;
    /** @var string */
    private $password;
    /** @var string|null */
    private $class;
    /** @var int */
    private $life = 3;
    /** @var RoomAction[]|null */
    private $blackListedRooms = null;
    /** @var Item[]|null */
    private $items = null;
    /** @var RoomAction|null */
    private $currentRoomAction = null;
    /** @var int */
    private $roomNumber = 1;
    /** @var Binder[]|Collection|null */
    private $binders;
    /** @var RoomAction[]|Collection|null */
    private $roomActions;

    public function __construct()
    {
        $this->blackListedRooms = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->roomActions = new ArrayCollection();
        $this->binders = new ArrayCollection();
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->name;
    }

    public function eraseCredentials()
    {
    }

    public function serialize()
    {
        return serialize(
            [
                $this->id,
                $this->name,
                $this->password
            ]
        );
    }

    public function unSerialize($serialized)
    {
        list(
            $this->id,
            $this->name,
            $this->password
            ) = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string|null
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

    /**
     * @param string|null $class
     */
    public function setClass(?string $class): void
    {
        $this->class = $class;
    }

    /**
     * @return int
     */
    public function getLife(): int
    {
        return $this->life;
    }

    /**
     * @param int $life
     */
    public function setLife(int $life): void
    {
        $this->life = $life;
    }

    /**
     * @return RoomAction|null
     */
    public function getCurrentRoomAction(): ?RoomAction
    {
        return $this->currentRoomAction;
    }

    /**
     * @param RoomAction $currentRoomAction
     */
    public function setCurrentRoomAction(RoomAction $currentRoomAction): void
    {
        $this->currentRoomAction = $currentRoomAction;
    }

    /**
     * @return int
     */
    public function getRoomNumber(): int
    {
        return $this->roomNumber;
    }

    /**
     * @param int $roomNumber
     */
    public function setRoomNumber(int $roomNumber): void
    {
        $this->roomNumber = $roomNumber;
    }

    /**
     * @return RoomAction[]|null|Collection
     */
    public function getBlackListedRooms(): Collection
    {
        return $this->blackListedRooms;
    }

    public function addBlackListedRoom(RoomAction $blackListedRoom): void
    {
        if (!$this->blackListedRooms->contains($blackListedRoom)) {
            $this->blackListedRooms->add($blackListedRoom);
        }
    }

    public function removeBlackListedRoom(RoomAction $blackListedRoom): void
    {
        if ($this->blackListedRooms->contains($blackListedRoom)) {
            $this->blackListedRooms->removeElement($blackListedRoom);
        }
    }

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): void
    {
        if (!$this->items->contains($item)) {
            $item->addUser($this);
            $this->items->add($item);
        }
    }

    public function removeItem(Item $item): void
    {
        if ($this->items->contains($item)) {
            $item->removeUser($this);
            $this->items->removeElement($item);
        }
    }

    public function resetBlackListedRoom()
    {
        $this->blackListedRooms = null;
    }

    public function resetItems()
    {
        $items = $this->getItems();

        foreach ($items as $item) {
            $this->removeItem($item);
        }
    }

    /**
     * @return Binder[]|Collection|null
     */
    public function getBinders(): Collection
    {
        return $this->binders;
    }

    public function addBinder(Binder $binder): void
    {
        if (!$this->binders->contains($binder)) {
            $this->binders->add($binder);
        }
    }

    public function removeBinder(Binder $binder): void
    {
        if ($this->binders->contains($binder)) {
            $this->binders->removeElement($binder);
        }
    }

    /**
     * @return RoomAction[]|Collection|null
     */
    public function getRoomActions(): Collection
    {
        return $this->roomActions;
    }

    public function addRoomAction(RoomAction $roomAction): void
    {
        if (!$this->roomActions->contains($roomAction)) {
            $this->roomActions->add($roomAction);
        }
    }

    public function removeRoomAction(RoomAction $roomAction): void
    {
        if ($this->roomActions->contains($roomAction)) {
            $this->roomActions->removeElement($roomAction);
        }
    }
}
