<?php
namespace App\Model;
use App\Model\Database\Database;
use App\Model\Authentication\Authentication;
use App\Model\Image\Image;
use App\Model\ProductCalculator\ProductCalculator;

class Facade extends Factory
{
    public function Database() :Database{
        return $this->createDatabase();
    }

    public function Authentication() :Authentication{
        return $this->createAuthentication();
    }

    public function Image() :Image{
        return $this->createImage();
    }

    public function ProductCalculator() :ProductCalculator{
        return $this->createProductCalculator();
    }
}
