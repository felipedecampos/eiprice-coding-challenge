<?php

declare(strict_types=1);

namespace App\Classes\V1\Interfaces;

use App\Models\V1\Product;
use Illuminate\Support\Collection;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Interface ChannelReportInterface
 * This interface was created to guarantee all Clients (Channels) created must have the methods bellow
 * Because the Report Service need to use these methods to generate the Report with all Clients (Channels)
 * All Client must have to be implemented with Singleton pattern to avoid multi instances on the report generation
 * @package App\Classes\V1
 */
interface ChannelReportInterface
{
    /**
     * Get all stores with Products and Prices
     * @param Product $product
     * @return Collection
     */
    public static function getStores(Product $product): Collection;

    /**
     * Get Stores list of the current pagination
     * @param Crawler $crawler
     * @param Product $product
     * @return Collection
     */
    public static function getStoresList(Crawler $crawler, Product $product): Collection;

    /**
     * Check if Stores pagination is still available
     * @param Crawler $crawler
     * @return bool
     */
    public static function isStoresPaginationAvailable(Crawler $crawler): bool;

    /**
     * GoogleReport clone.
     */
    public function __clone();

    /**
     * GoogleReport wakeup.
     */
    public function __wakeup();

    /**
     * Get self instance
     * @return GoogleReport
     */
    public static function getInstance();
}
