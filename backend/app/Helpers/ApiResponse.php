<?php 

namespace App\Helpers;

use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    /**
     * Success Response
     *
     * @param mixed $data
     * @param string $message
     * @param int $code
     * @param array $meta
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($data, $message = 'Success', $code = Response::HTTP_OK, $meta = [])
    {
        $response = [
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ];

        if (!empty($meta)) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $code);
    }

    /**
     * Error Response
     *
     * @param string $message
     * @param int $code
     * @param mixed $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error($message = 'Error', $code = Response::HTTP_BAD_REQUEST, $errors = null)
    {
        $response = [
            'status' => 'error',
            'message' => $message,
        ];

        if (!is_null($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }
}
