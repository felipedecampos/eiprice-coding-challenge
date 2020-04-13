<?php

declare(strict_types=1);

namespace App\Classes\V1\Channels;

use App\Classes\V1\ChannelReport;
use App\Classes\V1\Interfaces\ChannelReportInterface;
use App\Models\V1\Product;
use App\Models\V1\Store;
use Exception;
use Illuminate\Support\Collection;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class GoogleReport (Client)
 * This class was implemented with Singleton pattern to avoid multi instances on the report generation
 * @package App\Classes\V1
 */
class GoogleReport extends ChannelReport implements ChannelReportInterface
{
    /**
     * @var GoogleReport
     */
    private static $instance;

    /**
     * @var string
     */
    private static $storesListPageLink;

    /**
     * @var int
     */
    private static $currentStartPage = 5;

    /**
     * @var int
     */
    private static $pageLimit = 5;

    /**
     * Get all Stores with Products + Prices
     * @param Product $product
     * @return Collection
     * @throws Exception
     */
    public static function getStores(Product $product): Collection
    {
        self::getInstance();

        $googleShopping = self::$client->request('GET', sprintf(
            'https://www.google.com/search?tbm=shop&q=%d',
            ($product->ean)
        ));

        $storesListLink = $googleShopping->selectLink('COMPARAR PREÇOS')->link()->getUri();

        self::$storesListPageLink = $storesListLink;

        do {
            $storesListCrawler = self::$client->request('GET', self::$storesListPageLink);

            if (! isset($stores)) {
                $stores = collect([]);
            }

            $stores = $stores->merge(self::getStoresList($storesListCrawler, $product));
        } while (self::isStoresPaginationAvailable($storesListCrawler));

        return $stores;
    }

    /**
     * Get Stores list of the current pagination
     * @param Crawler $crawler
     * @param Product $product
     * @return Collection
     */
    public static function getStoresList(Crawler $crawler, Product $product): Collection
    {
        $storesListWrapper = $crawler->filter('#online >.t9KcM');

        return collect($storesListWrapper->each(function (Crawler $node, int $i) use ($product) {
            $rawPrice    = preg_replace('/[^0-9,"."]/', '', $node->filter('.OF7I2d >.WwE9ce >b')->text());
            $stringPrice = str_replace(',', '.', str_replace('.', '', $rawPrice));
            $floatPrice  = number_format((float) $stringPrice, 2, '.', '');

            return new Store([
                'name'        => $node->filter('* >a')->text(),
                'product_uri' => $node->filter('* >a')->attr('href'),
                'product'     => new Product([
                    'ean'         => $product->ean ?? null,
                    'description' => $product->description ?? null,
                    'price'       => $floatPrice,
                ]),
            ]);
        }));
    }

    /**
     * Check if Stores pagination is still available
     * @param Crawler $crawler
     * @return bool
     */
    public static function isStoresPaginationAvailable(Crawler $crawler): bool
    {
        if (! $crawler->filter('.P44d0c >.EivzQd >.Siw69e a:last-of-type')->count()) {
            return false;
        }

        self::$storesListPageLink = self::handlePage(
            $crawler->filter('.P44d0c >.EivzQd >.Siw69e a:last-of-type')->link()->getUri()
        );

        return true;
    }

    /**
     * Handle Google pagination pattern to paginate the Stores list
     * @param string $link
     * @return string
     */
    private static function handlePage(string $link)
    {
        $url = parse_url($link);

        parse_str($url['query'], $query);

        $start = isset($query['prds']) && false !== strpos($query['prds'], 'start:')
            ? preg_replace('/(start\:)\K\d+/', self::$currentStartPage, $query['prds'])
            : 'start:' . self::$currentStartPage;

        $query['prds'] = $start;
        $url['query']  = urldecode(http_build_query($query));

        $newLink = $url['scheme'] . '://' . $url['host'] . $url['path'] . '?' . $url['query'] . '#' . $url['fragment'];

        self::$currentStartPage += self::$pageLimit;

        return $newLink;
    }

    /**
     * GoogleReport clone.
     */
    public function __clone()
    {
    }

    /**
     * GoogleReport wakeup.
     */
    public function __wakeup()
    {
    }

    /**
     * Get self instance
     * @return GoogleReport
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
