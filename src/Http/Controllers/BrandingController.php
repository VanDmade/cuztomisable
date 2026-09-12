<?php

namespace VanDmade\Cuztomisable\Http\Controllers;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Serves the package's own images/ directory directly (logo, banner, favicon, profile
 * placeholder) instead of publishing them into the host's public/ folder as raw static files.
 * A raw static file gets served by the web server with no cache headers at all (confirmed via
 * `php artisan serve` returning none), so the browser re-downloads it on every page load. Since
 * this route always goes through Laravel, it can set a long-lived, cacheable response instead -
 * works the same locally and in production, rather than depending on host web server config.
 */
class BrandingController extends CuztomisableController
{

    public function show(string $filename): BinaryFileResponse
    {
        $directory = realpath(__DIR__.'/../../../images');
        $path = realpath($directory.'/'.$filename);
        if (!$path || !str_starts_with($path, $directory) || !is_file($path)) {
            abort(404);
        }
        return response()->file($path, [
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

}
