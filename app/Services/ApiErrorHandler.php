<?php

namespace App\Services;

class ApiErrorHandler
{
    /**
     * Przetwarza odpowiedź błędu API i zwraca odpowiedni komunikat.
     *
     * @param array $errorResponse
     * @return array
     */
    public static function handle(array $errorResponse): array
    {
        $code = $errorResponse['code'] ?? null;
        $message = $errorResponse['message'] ?? 'Wystąpił nieoczekiwany błąd.';

        switch ($code) {
            case 400:
                $friendlyMessage = 'Żądanie jest nieprawidłowe. Sprawdź dane wejściowe.';
                break;
            case 404:
                $friendlyMessage = 'Nie znaleziono zasobu. Upewnij się, że ID jest poprawne.';
                break;
            case 500:
                $friendlyMessage = 'Wystąpił błąd po stronie serwera. Spróbuj ponownie później.';
                break;
            default:
                $friendlyMessage = 'Wystąpił nieoczekiwany błąd. Skontaktuj się z administratorem.';
        }

        return [
            'status' => 'error',
            'code' => $code,
            'message' => $friendlyMessage,
            'debug' => app()->isLocal() ? $message : null,
        ];
    }
}
