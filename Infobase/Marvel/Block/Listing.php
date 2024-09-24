<?php

namespace Infobase\Marvel\Block;

use Magento\Framework\View\Element\Template;

/**
 * Class Listing
 *
 * This class handles the listing of characters from the Marvel API.
 */
class Listing extends Template
{
    private $results = null;
    /**
     * Prepare layout.
     *
     * @return void
     */
    public function _prepareLayout(): void
    {
        parent::_prepareLayout();
    }

    /**
     * Get the public API key.
     *
     * @return string
     */
    public function getPublicKey(): string
    {
        return '61ff0efe1bb8bc93f162c92d2d6e3c49';
    }

    /**
     * Get the private API key.
     *
     * @return string
     */
    public function getPrivateKey(): string
    {
        return '195e4682f8d9ed3517b3cf06f7584e8aab2d0486';
    }

    /**
     * Generate the API URL with the required parameters.
     *
     * @return string
     */
    public function getApiUrl(): string
    {
        $ts = time();
        $privateKey = $this->getPrivateKey();
        $publicKey = $this->getPublicKey();
        $hash = md5($ts . $privateKey . $publicKey);

        return "https://gateway.marvel.com/v1/public/characters?ts={$ts}&apikey={$publicKey}&hash={$hash}";
    }

    /**
     * Fetch the response from the API and decode it.
     *
     * @return array
     */
    public function getResponse(): array
    {
        $response = file_get_contents($this->getApiUrl());
        $data = json_decode($response, true);

        return $data['data']['results'] ?? []; // Return an empty array if 'results' is not set
    }

    /**
     * Get character results, marking some as rare.
     *
     * @return array
     */
    public function getResults(): array
    {
        if($this->results) {
            return $this->results;
        }
        
        $results = $this->getResponse();
        $numRaros = ceil(count($results) * 0.1);
        $totalCharacters = count($results);
        mt_srand(time());
        $indicesRaros = array_rand(range(0, $totalCharacters - 1), $numRaros);

        if (!is_array($indicesRaros)) {
            $indicesRaros = [$indicesRaros];
        }

        foreach ($indicesRaros as $index) {
            $results[$index]['isRare'] = true;
        }
        $this->results = $results;

        return $this->results;
    }

    /**
     * Get the total number of pages based on results.
     *
     * @return int
     */
    public function getTotalPages(): int
    {
        $results = $this->getResults();
        $totalItems = count($results);

        return ceil($totalItems / 6);
    }

    /**
     * Get the current page number from the request.
     *
     * @return int
     */
    public function getCurrentPage(): int
    {
        $totalPages = $this->getTotalPages();
        $page = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
        $page = max($page, 1);

        return min($page, $totalPages);
    }

    /**
     * Get paginated results for the current page.
     *
     * @return array
     */
    public function getPagination(): array
    {
        $results = $this->getResults();
        $page = $this->getCurrentPage();

        return array_slice($results, ($page - 1) * 6, 6);
    }

    /**
     * Get the image source URL for a character.
     *
     * @param array $item Character item data.
     * @return string
     */
    public function getImageSrc(array $item): string
    {
        return str_replace('http', 'https', $item['thumbnail']['path']) . '.' . $item['thumbnail']['extension'];
    }
}
