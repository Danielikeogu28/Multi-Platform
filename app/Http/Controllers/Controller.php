<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * @OA\Info(
     *    title="Swagger with Laravel",
     *    version="1.0.0",
     * )
     * @OA\SecurityScheme(
     *     type="http",
     *     securityScheme="bearerAuth",
     *     scheme="bearer",
     *     bearerFormat="JWT"
     * )

     */
}
