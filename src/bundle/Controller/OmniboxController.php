<?php

declare(strict_types=1);

namespace AdamWojs\EzPlatformOmniboxBundle\Controller;

use AdamWojs\EzPlatformOmniboxBundle\Service\QueryString;
use AdamWojs\EzPlatformOmniboxBundle\Service\SuggestionProviderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

final class OmniboxController extends AbstractController
{
    /** @var SuggestionProviderInterface */
    private $suggestionProvider;

    public function __construct(SuggestionProviderInterface $omniboxService)
    {
        $this->suggestionProvider = $omniboxService;
    }

    public function searchAction(Request $request): JsonResponse
    {
        $query = new QueryString($request->query->get('query', ''));

        if ($query->isEmpty()) {
            // No suggestions for empty query
            return new JsonResponse([]);
        }

        $suggestions = $this->suggestionProvider->getSuggestions($query);

        return new JsonResponse(iterator_to_array($suggestions));
    }
}