<?php
namespace App\Entity;

use App\Contracts\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User implements UserInterface {
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $name;

    #[ORM\Column(type: 'string', unique: true)]
    private string $email;

    #[ORM\Column(type: 'string')]
    private string $password;

    // One user can have many tasks
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'user')]
    private Collection $tasks;

    public function __construct(array $data) {
        $this->tasks = new ArrayCollection();
        $this->name = $data["name"];
        $this->email = $data["email"];


    }

    public function getId(): int
    {
        return $this->id;
    }

	public function getPassword(): string 
    {
        return $this->password;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setPassword(string $hashedPassword)
    {
        $this->password = $hashedPassword;
    }
}