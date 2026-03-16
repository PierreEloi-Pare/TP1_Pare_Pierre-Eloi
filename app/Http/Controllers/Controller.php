<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "Equipment Rental API",
    description: "Un API REST d'enregistrement de location d'équipement sportif."
)]

abstract class Controller
{
    //
}
