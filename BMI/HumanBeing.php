<?php

class HumanBeing
{
    private $height;
    private $weight;
    private $bmi;

    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function calculateBmi()
    {
        $heightInMeters = $this->getHeight() / 100;
        $this->bmi = $this->getWeight() / ($heightInMeters * $heightInMeters);
    }

    public function getBmi()
    {
        return $this->bmi;
    }
}
