<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\Academic_year;
use App\Models\Blood;
use App\Models\Claass;
use App\Models\Gender;
use App\Models\Grade;
use App\Models\Nationality;
use App\Models\Religtion;
use App\Traits\generalTrait;
use Illuminate\Http\Request;

class GetAllSeedController extends Controller
{
    use generalTrait;
    public function getAllSeed() {
        $academics = Academic_year::query()->get();
        $bloods = Blood::query()->get();
        $classes = Claass::query()->with('classroom')->get();
        $genders = Gender::query()->get();
        $grades = Grade::query()->get();
        $religtions = Religtion::query()->get();
        $nationality = Nationality::query()->get();

        $data['Academic Years'] = $academics;
        $data['bloods'] = $bloods;
        $data['classes'] = $classes;
        $data['genders'] = $genders;
        $data['grades'] = $grades;
        $data['religtions'] = $religtions;
        $data['nationality'] = $nationality;

        return $this->returnData('data', $data, 'all seed');

    }
}
