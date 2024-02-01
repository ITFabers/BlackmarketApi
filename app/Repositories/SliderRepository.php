<?php
// app/Repositories/SliderRepository.php

namespace App\Repositories;

use App\Models\Slider;

class SliderRepository extends FrontBaseRepository {
    public function __construct(Slider $slider) {
        parent::__construct($slider);
    }

    public function getSlides() {
        return $this->allWhere(['status' => 1], ['column' => 'created_at', 'direction' => 'desc']);
    }
}
