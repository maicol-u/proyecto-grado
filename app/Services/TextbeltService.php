<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class TextbeltService
{
    protected string $url;

    public function __construct()
    {
        $this->url = config('services.textbelt.url', 'https://textbelt.com/text');
    }

    /**
     * Envía un SMS mediante Textbelt.
     *
     * @param string $phone Número en formato internacional (Ej: +573001234567)
     * @param string $message Mensaje a enviar
     * @return array
     */
    public function send(string $phone, string $message): array
    {
        $response = Http::asForm()
            ->timeout(10)
            ->post($this->url, [
                'phone'   => $this->normalizeColombianPhone($phone),
                'message' => $message,
                'key'     => config('services.textbelt.key'),
                'sender'  => config('services.textbelt.sender', 'Telematica'),
            ]);

        if (! $response->successful()) {
            Log::error('Error HTTP al enviar SMS mediante Textbelt.', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            throw new RequestException($response);
        }

        $payload = $response->json();

        if (! is_array($payload) || ! ($payload['success'] ?? false)) {
            $error = $payload['error'] ?? 'Respuesta invalida de Textbelt.';

            Log::error('Textbelt rechazo el SMS.', [
                'phone' => $phone,
                'response' => $payload,
            ]);

            throw new RuntimeException($error);
        }

        return $payload;
    }

    private function normalizeColombianPhone(string $phone): string
    {
        $normalized = preg_replace('/\D+/', '', $phone) ?? '';

        if (preg_match('/^57(3\d{9})$/', $normalized, $matches)) {
            return '+57' . $matches[1];
        }

        if (preg_match('/^(3\d{9})$/', $normalized, $matches)) {
            return '+57' . $matches[1];
        }

        throw new RuntimeException('El numero celular debe ser un movil colombiano valido.');
    }
}