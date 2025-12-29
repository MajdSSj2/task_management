<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

#[ORM\Entity]
#[ORM\Table(name: 'tasks')]
class Task {
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

    public function __construct(User $user, array $data) {
        $this->user = $user;
        $this->title = $data['title'];
        $this->description = $data['description'] ?? null;
        $this->priority = (int)$data['priority'];
        $this->done = (bool)$data['done'];
        $this->createdAt = new DateTime();
    }

    // --- Getters ---

    public function getId(): int {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function getDue(): ?DateTime {
        return $this->due;
    }

    public function getDone(): bool {
        return $this->done;
    }

    public function getPriority(): int {
        return $this->priority;
    }

    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }

    public function getDeletedAt(): ?DateTime {
        return $this->deletedAt;
    }

    public function getUser(): User {
        return $this->user;
    }

    // --- Setters (Optional but recommended for updates) ---

    public function setTitle(string $title): self {
        $this->title = $title;
        return $this;
    }

    public function setDone(bool $done): self {
        $this->done = $done;
        return $this;
    }

    public function setPriority(int $priority): self {
        $this->priority = $priority;
        return $this;
    }
       public function setDescription(string $description): self {
        $this->description = $description;
        return $this;
    }

}