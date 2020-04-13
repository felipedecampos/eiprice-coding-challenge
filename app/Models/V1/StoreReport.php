<?php

declare(strict_types=1);

namespace App\Models\V1;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StoreReport
 * @package App\Classes\V1
 */
class StoreReport extends Model
{
    /**
     * @var string
     */
    public $product;

    /**
     * @var float
     */
    public $ean;

    /**
     * @var string
     */
    public $channel;

    /**
     * @var string
     */
    public $store;

    /**
     * @var float
     */
    public $base_price;

    /**
     * @var float
     */
    public $channel_price;

    /**
     * @var float
     */
    public $gap;

    /**
     * @var string
     */
    public $status;

    /**
     * DateTime
     */
    public $created_at;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product',
        'ean',
        'channel',
        'store',
        'base_price',
        'channel_price',
        'gap',
        'status',
        'created_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
    ];

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d/m/Y H:i:s');
    }
}
