<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlanRepository;

/**
 * @ORM\Table(name="app_plan")
 * @ORM\Entity(repositoryClass=PlanRepository::class)
 */
class Plan
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var PlanGroup
     *
     * @ORM\ManyToOne(targetEntity="PlanGroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="plan_group_id", referencedColumnName="id", nullable=false)
     * })
     */
    protected $group;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $frequency;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    protected $currency;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $price;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default"="1"})
     */
    protected $is_enabled = 1;

    /**
     * @ORM\Column(type="boolean", nullable=false, options={"default"="0"})
     */
    protected $is_default = 0;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     */
    protected $provider;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $external_id;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $created_at;

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

    public function getFrequency(): ?string
    {
        return $this->frequency;
    }

    public function setFrequency(?string $frequency): self
    {
        $this->frequency = $frequency;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function isIsEnabled(): ?bool
    {
        return $this->is_enabled;
    }

    public function setIsEnabled(bool $is_enabled): self
    {
        $this->is_enabled = $is_enabled;

        return $this;
    }

    public function isIsDefault(): ?bool
    {
        return $this->is_default;
    }

    public function setIsDefault(bool $is_default): self
    {
        $this->is_default = $is_default;

        return $this;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function setProvider(?string $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function getExternalId(): ?string
    {
        return $this->external_id;
    }

    public function setExternalId(?string $external_id): self
    {
        $this->external_id = $external_id;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getGroup(): ?PlanGroup
    {
        return $this->group;
    }

    public function setGroup(?PlanGroup $group): self
    {
        $this->group = $group;

        return $this;
    }
}