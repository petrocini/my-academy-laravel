<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="My Workout API",
 *     version="1.0.0",
 *     description="Documentação da API de treino e exercícios"
 * )
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Laravel Docker"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer"
 * )
 */
class SwaggerBase {}
