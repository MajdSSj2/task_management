<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

#[ORM\Entity]
#[ORM\Table(name: 'tasks')]
class Task implements \JsonSerializable
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $title;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $due;

    #[ORM\Column(type: 'boolean')]
    private bool $done = false;

    #[ORM\Column(type: 'smallint')]
    private int $priority = 1; // e.g., 1=Low, 2=Med, 3=High

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $deletedAt = null; // For Soft Deletes

    // MANY tasks belong to ONE user
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    private User $user;

    public function __construct(User $user, array $data)
    {

        if (!empty($data['due'])) {
            $dueObject = DateTime::createFromFormat('d-m-Y g:i:s A', $data['due']);
            $this->due = $dueObject ?: null;
        } else {
            $this->due = null;
        }

        $this->user = $user;
        $this->title = $data['title'];
        $this->description = $data['description'] ?? null;
        $this->priority = (int)($data['priority'] ?? 1);
        $this->done = (bool)($data['done'] ?? false);
        $this->createdAt = new DateTime();
    }

    // --- Getters ---

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getDue(): ?DateTime
    {
        return $this->due;
    }

    public function getDone(): bool
    {
        return $this->done;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getDeletedAt(): ?DateTime
    {
        return $this->deletedAt;
    }

    public function getUser(): User
    {
        return $this->user;
    }



    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function setDone(bool $done): self
    {
        $this->done = $done;
        return $this;
    }

 public function setPriority(int|string $priority): self
    {
        $this->priority = (int) $priority;
        return $this;
    }
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function setDeletedAt(DateTime $date)
    {
        $this->deletedAt = $date;
    }
    public function setCreatedAt(DateTime $date)
    {
        $this->createdAt  = $date;
    }

public function setDue(DateTime|string|null $date): self
    {
        if (is_string($date)) {
            // Match the format used in your constructor/JSON
            $this->due = DateTime::createFromFormat('Y-m-d g:i:s A', $date) ?: new DateTime($date);
        } else {
            $this->due = $date;
        }
        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description ?? '',
            'due' => $this->due ? $this->due->format('d-m-Y g:i:s A') : null,
            'done' => $this->done,
            'priority' => $this->priority,
            'createdAt' => $this->createdAt->format('Y-m-d g:i:s A'),
            'user_id' =>$this->user->getId()
        ];
    }
}
