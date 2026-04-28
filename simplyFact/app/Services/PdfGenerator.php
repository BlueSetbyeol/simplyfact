<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

readonly class PdfGenerator implements Responsable
{
    private string $apiUrl;

    private string $html;

    private Collection $merge;

    private string $document;

    public function __construct()
    {
        $this->apiUrl = config('services.pdf.api_url');
    }

    public function view(string $name, array $data = []): self
    {
        $this->html = view($name, $data)->toHtml();

        return $this;
    }

    public function html(string $html): self
    {
        $this->html = $html;

        return $this;
    }

    public function merge(iterable $urls): self
    {
        $this->merge = collect($urls)->map(fn ($url) => str_ends_with(parse_url($url, PHP_URL_PATH), '.pdf')
            ? $url
            : "{$this->apiUrl}?url={$url}"
        );

        return $this;
    }

    public function getDocument(): string
    {
        if (! isset($this->document)) {
            $response = Http::post($this->apiUrl, [
                'html' => $this->html,
                'merge' => $this->merge ?? null,
            ]);

            if ($response->failed()) {
                throw new RuntimeException("Failed to generate PDF: {$response->body()}");
            }

            $this->document = $response->body();
        }

        return $this->document;
    }

    public function inlineResponse(string $filename = ''): Response
    {
        return new Response($this->getDocument(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"{$filename}\"",
        ]);
    }

    public function downloadResponse(string $filename = ''): Response
    {
        return new Response($this->getDocument(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function save(string $path, ?string $fileName = null): string
    {
        $filename ??= Str::random(40).'.pdf';

        $path = mb_rtrim($path, '/').'/'.$filename;

        Storage::put($path, $this->getDocument());

        return $path;
    }

    public function toResponse($request)
    {
        return $this->inlineResponse();
    }
}
