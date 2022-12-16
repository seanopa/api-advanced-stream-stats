<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlanGroupFeatureRepository;

/**
 * @ORM\Table(name="app_plan_group_feature")
 * @ORM\Entity(repositoryClass=PlanGroupFeatureRepository::class)
 */
class PlanGroupFeature
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
     *   @ORM\JoinColumn(name="group_id", referencedColumnName="id", nullable=false)
     * })
     */
    protected $group;

    /**
     * @var PlanFeature
     *
     * @ORM\ManyToOne(targetEntity="PlanFeature")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="feature_id", referencedColumnName="id", nullable=false)
     * })
     */
    protected $feature;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getFeature(): ?PlanFeature
    {
        return $this->feature;
    }

    public function setFeature(?PlanFeature $feature): self
    {
        $this->feature = $feature;

        return $this;
    }
}