<?php

namespace App\Entity;

use App\Repository\ObstacleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ObstacleRepository::class)]
class Obstacle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $difficulty_int = null;

    #[ORM\Column(nullable: true)]
    private ?int $difficulty_str = null;

    #[ORM\Column(nullable: true)]
    private ?int $difficulty_dex = null;

    #[ORM\Column]
    private ?int $cost_reward_time = null;

    #[ORM\Column]
    private ?int $cost_reward_health = null;

    #[ORM\Column]
    private ?int $cost_reward_stamina = null;

    #[ORM\Column]
    private ?int $cost_reward_int = null;

    #[ORM\Column]
    private ?int $cost_reward_str = null;

    #[ORM\Column]
    private ?int $cost_reward_dex = null;

    #[ORM\Column]
    private ?int $cost_reward_lck = null;

    #[ORM\Column]
    private ?int $cost_reward_spd = null;

    #[ORM\Column]
    private ?int $cost_reward_con = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDifficultyInt(): ?int
    {
        return $this->difficulty_int;
    }

    public function setDifficultyInt(?int $difficulty_int): self
    {
        $this->difficulty_int = $difficulty_int;

        return $this;
    }

    public function getDifficultyStr(): ?int
    {
        return $this->difficulty_str;
    }

    public function setDifficultyStr(?int $difficulty_str): self
    {
        $this->difficulty_str = $difficulty_str;

        return $this;
    }

    public function getDifficultyDex(): ?int
    {
        return $this->difficulty_dex;
    }

    public function setDifficultyDex(?int $difficulty_dex): self
    {
        $this->difficulty_dex = $difficulty_dex;

        return $this;
    }

    public function getCostRewardTime(): ?int
    {
        return $this->cost_reward_time;
    }

    public function setCostRewardTime(int $cost_reward_time): self
    {
        $this->cost_reward_time = $cost_reward_time;

        return $this;
    }

    public function getCostRewardHealth(): ?int
    {
        return $this->cost_reward_health;
    }

    public function setCostRewardHealth(int $cost_reward_health): self
    {
        $this->cost_reward_health = $cost_reward_health;

        return $this;
    }

    public function getCostRewardStamina(): ?int
    {
        return $this->cost_reward_stamina;
    }

    public function setCostRewardStamina(int $cost_reward_stamina): self
    {
        $this->cost_reward_stamina = $cost_reward_stamina;

        return $this;
    }

    public function getCostRewardInt(): ?int
    {
        return $this->cost_reward_int;
    }

    public function setCostRewardInt(int $cost_reward_int): self
    {
        $this->cost_reward_int = $cost_reward_int;

        return $this;
    }

    public function getCostRewardStr(): ?int
    {
        return $this->cost_reward_str;
    }

    public function setCostRewardStr(int $cost_reward_str): self
    {
        $this->cost_reward_str = $cost_reward_str;

        return $this;
    }

    public function getCostRewardDex(): ?int
    {
        return $this->cost_reward_dex;
    }

    public function setCostRewardDex(int $cost_reward_dex): self
    {
        $this->cost_reward_dex = $cost_reward_dex;

        return $this;
    }

    public function getCostRewardLck(): ?int
    {
        return $this->cost_reward_lck;
    }

    public function setCostRewardLck(int $cost_reward_lck): self
    {
        $this->cost_reward_lck = $cost_reward_lck;

        return $this;
    }

    public function getCostRewardSpd(): ?int
    {
        return $this->cost_reward_spd;
    }

    public function setCostRewardSpd(int $cost_reward_spd): self
    {
        $this->cost_reward_spd = $cost_reward_spd;

        return $this;
    }

    public function getCostRewardCon(): ?int
    {
        return $this->cost_reward_con;
    }

    public function setCostRewardCon(int $cost_reward_con): self
    {
        $this->cost_reward_con = $cost_reward_con;

        return $this;
    }
}
