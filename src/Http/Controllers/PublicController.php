<?php

namespace Botble\Series\Http\Controllers;

use Botble\Series\Models\Series;
use Botble\Series\Repositories\Interfaces\SeriesInterface;
use Botble\Series\Services\SeriesService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Response;
use SeoHelper;
use SlugHelper;
use Theme;

class PublicController extends Controller
{
    /**
     * @param string $slug
     * @param SeriesService $seriesService
     * @return RedirectResponse|Response
     */
    public function getSerries($slug, SeriesService $seriesService)
    {
        $slug = SlugHelper::getSlug($slug, SlugHelper::getPrefix(Series::class));

        if (!$slug) {
            abort(404);
        }

        $data = $seriesService->handleFrontRoutes($slug);

        if (isset($data['slug']) && $data['slug'] !== $slug->key) {
            return redirect()->to(route('public.single', SlugHelper::getPrefix(Series::class) . '/' . $data['slug']));
        }

        return Theme::scope($data['view'], $data['data'], $data['default_view'])
            ->render();
    }
}
