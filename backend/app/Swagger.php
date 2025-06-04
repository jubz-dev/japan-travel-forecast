<?php

namespace App;

use OpenApi\Annotations as OA;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         version="1.0.0",
 *         title="Bizmates Exam API",
 *         description="This is the API documentation for the Bizmates Exam project.",
 *         @OA\Contact(
 *             email="support@bizmates.com",
 *             name="Bizmates Dev Team"
 *         ),
 *         @OA\License(
 *             name="MIT",
 *             url="https://opensource.org/licenses/MIT"
 *         )
 *     ),
 *     @OA\Server(
 *         url=L5_SWAGGER_CONST_HOST,
 *         description="Main API server"
 *     )
 * )
 */
class Swagger
{
    //
}
