<?php

declare(strict_types=1);

use App\Models\V1\Channel;
use Illuminate\Database\Seeder;

/**
 * Class ChannelsTableSeeder
 */
class ChannelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Channel::firstOrCreate(['key' => 'google', 'name' => 'Google'], ['key' => 'google', 'name' => 'Google']);
    }
}
