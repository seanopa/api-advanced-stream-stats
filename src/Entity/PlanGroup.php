<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlanGroupRepository;

/**
 * @ORM\Table(name="app_plan_group")
 * @ORM\Entity(repositoryClass=PlanGroupRepository::class)
 */
class PlanGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
}