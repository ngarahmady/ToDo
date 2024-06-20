<?php

namespace App\Exceptions;

use Throwable;
use App\Enums\ApiHttpStatus;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use App\Exceptions\_Base\BaseException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     *  Report or log an exception.
     *  This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param Throwable $e
     * @return void
     * @throws Throwable
     */
    public function report(Throwable $e): void
    {

        parent::report($e);
    }

    /**
     * @param $request
     * @param Throwable $e
     * @return JsonResponse|RedirectResponse|Response|\Symfony\Component\HttpFoundation\Response
     * @throws Throwable
     */
    public function render($request, Throwable $e): Response|JsonResponse|\Symfony\Component\HttpFoundation\Response|RedirectResponse
    {
        if ($request->getMethod() == 'OPTIONS') {
            $result = [
                'status' => 1,
                'message' => 'Ready...!',
                'data' => ['service' => 'up'],
            ];

            return response()->json($result, 204);
        }

        $error = $this->prepareResponseProcess($e);

        $result = [
            'status' => 0,
            'message' => $error['message'],
            'data' => $this->getOptions($e)
        ];

        return response()->json($result, $error['code']);
    }


    /**
     * @param $e
     * @return array
     */
    public function prepareResponseProcess($e): array
    {
        return match (true) {
            $e instanceof BaseException => ['message' => $e->getMessage(), 'code' => $e->getCode()],
            $e instanceof ValidationException => ['message' => trans('validation.error'), 'code' => ApiHttpStatus::VALIDATION],
            $e instanceof NotFoundHttpException => ['message' => trans('exception._base.not_found.route'), 'code' => $e->getStatusCode()],
            $e instanceof AuthenticationException => ['message' => $e->getMessage(), 'code' => $e->getCode() ?? ApiHttpStatus::UNAUTHORIZED],
            $e instanceof ThrottleRequestsException => ['message' => trans('exception._base.throttle'), 'code' => ApiHttpStatus::THROTTLE_REQUESTS],
            default => ['message' => trans('exception._base.default'), 'code' => ApiHttpStatus::BAD_REQUEST]
        };
    }

    /**
     * @param $e
     * @return array
     */
    public function getOptions($e): array
    {
        if ($e instanceof ValidationException) {
            $options['errors'] = $e->errors();
        }

        if (config('app.debug')) {
            $options['debug'] = [
                'environment' => config('app.env'),
                'message' => $e->getMessage(),
                "file" => $e->getFile(),
                "line" => $e->getLine(),
                'input' => app('request')->all() ?? [],
            ];
        }

        return $options ?? [];
    }
}
