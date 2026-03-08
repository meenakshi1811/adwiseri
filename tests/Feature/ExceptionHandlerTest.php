<?php

namespace Tests\Feature;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class ExceptionHandlerTest extends TestCase
{
    public function test_json_not_found_returns_404_payload(): void
    {
        Route::get('/_test/not-found', function () {
            throw new NotFoundHttpException('Record not found.');
        });

        $response = $this->getJson('/_test/not-found');

        $response
            ->assertStatus(404)
            ->assertJson([
                'error' => [
                    'code' => 404,
                    'message' => 'Record not found.',
                ],
            ]);
    }

    public function test_json_payment_required_returns_402_payload(): void
    {
        Route::get('/_test/payment-required', function () {
            throw new HttpException(402, 'Payment is required for this action.');
        });

        $response = $this->getJson('/_test/payment-required');

        $response
            ->assertStatus(402)
            ->assertJson([
                'error' => [
                    'code' => 402,
                    'message' => 'Payment is required for this action.',
                ],
            ]);
    }

    public function test_json_authorization_exception_returns_403_payload(): void
    {
        Route::get('/_test/forbidden', function () {
            throw new AuthorizationException('You are not allowed to access this resource.');
        });

        $response = $this->getJson('/_test/forbidden');

        $response
            ->assertStatus(403)
            ->assertJson([
                'error' => [
                    'code' => 403,
                    'message' => 'Forbidden',
                ],
            ]);
    }

    public function test_json_throttle_exception_returns_429_payload(): void
    {
        Route::get('/_test/throttle', function () {
            throw new ThrottleRequestsException('Too many attempts.');
        });

        $response = $this->getJson('/_test/throttle');

        $response
            ->assertStatus(429)
            ->assertJson([
                'error' => [
                    'code' => 429,
                    'message' => 'Too many attempts.',
                ],
            ]);
    }
}
