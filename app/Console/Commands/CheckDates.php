<?php

namespace App\Console\Commands;

use Log;
use App\Models\Product;
use Illuminate\Support\Carbon;
// use App\Jobs\ExpireQuote;
use Illuminate\Console\Command;


class CheckDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $products = Product::all();
        // \Log::info("Cron is working fine!3456fghj");
        foreach ($products as $product) {
            $start_date = Carbon::today();
            $expiry_date = Carbon::parse($product->expiry_date);

            if ($start_date->greaterThan($expiry_date)) {
                $product->start_date = null;
                $product->expiry_date = null;
                $product->d_per = 0;

                $product->save();
            }
        }
    }



}
