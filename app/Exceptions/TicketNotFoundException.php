<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class TicketNotFoundException extends Exception
{
    protected $message;
    protected $statusCode;

    /**
     * Constructor to initialize the exception with a custom message and status code.
     *
     * @param string $message
     * @param int $statusCode
     */
    public function __construct(string $message = "Ticket not found", int $statusCode = Response::HTTP_NOT_FOUND)
    {
        $this->message = $message;
        $this->statusCode = $statusCode;
        parent::__construct($message, $statusCode);
    }

    /**
     * Report the exception (optional).
     */
    public function report()
    {
        // Log the error or perform other actions when this exception is thrown
        logger()->error($this->message);
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        return response()->json([
            'error' => $this->message,
        ], $this->statusCode);
    }
}
