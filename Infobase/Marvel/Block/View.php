<?php

namespace Infobase\Marvel\Block;

use Magento\Framework\View\Element\Template;

/**
 * Class View
 *
 * @package Infobase\Marvel\Block
 */
class View extends Template
{
    /**
     * Prepares the layout for the block.
     *
     * @return void
     */
    protected function _prepareLayout(): void
    {
        parent::_prepareLayout();
    }

    /**
     * Returns the public API key.
     *
     * @return string
     */
    public function getPublicKey(): string
    {
        return '61ff0efe1bb8bc93f162c92d2d6e3c49';
    }

    /**
     * Returns the private API key.
     *
     * @return string
     */
    public function getPrivateKey(): string
    {
        return '195e4682f8d9ed3517b3cf06f7584e8aab2d0486';
    }

    /**
     * Constructs the API URL for a given endpoint.
     *
     * @param string $endpoint The endpoint to construct the URL for.
     * @return string The constructed API URL.
     */
    public function getApiUrl(string $endpoint): string
    {
        $ts = time();
        $privateKey = $this->getPrivateKey();
        $publicKey = $this->getPublicKey();
        $hash = md5($ts . $privateKey . $publicKey);

        return sprintf(
            'https://gateway.marvel.com/v1/public/%s?ts=%s&apikey=%s&hash=%s',
            $endpoint,
            $ts,
            $publicKey,
            $hash
        );
    }

    /**
     * Fetches character data from the API by character ID.
     *
     * @param int $characterId The ID of the character.
     * @return array|null The character data or null if not found.
     */
    public function fetchCharacterData(int $characterId): ?array
    {
        $apiUrl = $this->getApiUrl("characters/$characterId");
        $response = file_get_contents($apiUrl);
        $data = json_decode($response, true);

        if (isset($data['data']['results'][0])) {
            return $data['data']['results'][0];
        }

        return null;
    }

    /**
     * Fetches comic details from a given resource URI.
     *
     * @param string $resourceURI The resource URI for the comic.
     * @return array|null The comic details or null if not found.
     */
    public function fetchComicDetails(string $resourceURI): ?array
    {
        $comicApiUrl = $resourceURI . $this->getParamApi();
        $response = file_get_contents($comicApiUrl);
        $data = json_decode($response, true);

        if (isset($data['data']['results'][0])) {
            return $data['data']['results'][0];
        }

        return null;
    }

    /**
     * Fetches detailed information for a given type of items.
     *
     * @param string $type The type of items being fetched.
     * @param array $items The list of items to fetch details for.
     * @return array The array of details for the items.
     */
    public function fetchDetailsFor(string $type, array $items): array
    {
        $details = [];
        foreach ($items as $item) {
            $detailData = $this->fetchComicDetails($item['resourceURI']);
            $details[] = $detailData ?? ['name' => $item['name'], 'details' => 'Detalhes não disponíveis'];
        }

        return $details;
    }

    /**
     * Returns the API parameters for requests.
     *
     * @return string The API parameters string.
     */
    public function getParamApi(): string
    {
        $ts = time();
        $hash = md5($ts . $this->getPrivateKey() . $this->getPublicKey());

        return sprintf('?ts=%s&apikey=%s&hash=%s', $ts, $this->getPublicKey(), $hash);
    }
}
