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

    private Collection $urls;

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

    public function urls(iterable $urls): self
    {
        $this->urls = collect($urls);

        return $this;
    }

    public function getDocument(): string
    {
        if (! isset($this->document)) {

            $response = Http::post($this->apiUrl, [
                'html' => $this->html,
                'merge' => $this->urls ?? [],
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
