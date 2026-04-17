<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send WhatsApp Template Message
     *
     * @param string $to        91XXXXXXXXXX
     * @param string $template  template name (hello_world / approved template)
     * @param array  $params    body variables (optional)
     * @param string|null $link button link (optional)
     */
    public static function sendTemplate(
        string $to,
        string $template,
        array $params = [],
        ?string $link = null
    ) {
        $url = "https://graph.facebook.com/"
            . config('services.whatsapp.version')
            . "/"
            . config('services.whatsapp.phone_number_id')
            . "/messages";

        $payload = [
            "messaging_product" => "whatsapp",
            "to" => $to,
            "type" => "template",
            "template" => [
                "name" => $template,
                "language" => [
                    "code" => "en_US"
                ]
            ]
        ];

        // 🔹 BODY VARIABLES ({{1}}, {{2}} etc.)
        if (!empty($params)) {
            $payload['template']['components'][] = [
                "type" => "body",
                "parameters" => collect($params)->map(fn ($text) => [
                    "type" => "text",
                    "text" => (string) $text
                ])->values()->toArray()
            ];
        }

        // 🔹 BUTTON LINK (CTA)
        if ($link) {
            $payload['template']['components'][] = [
                "type" => "button",
                "sub_type" => "url",
                "index" => "0",
                "parameters" => [
                    [
                        "type" => "text",
                        "text" => $link
                    ]
                ]
            ];
        }

        $response = Http::withToken(config('services.whatsapp.token'))
            ->timeout(20)
            ->post($url, $payload);

        if (! $response->successful()) {
            Log::error('WhatsApp API Error', [
                'to'       => $to,
                'template' => $template,
                'payload'  => $payload,
                'response' => $response->body(),
            ]);
        }

        return $response;
    }
}
