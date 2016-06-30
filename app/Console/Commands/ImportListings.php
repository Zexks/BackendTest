<?php

namespace App\Console\Commands;

use XmlParser;
use Illuminate\Console\Command;

class ImportListings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ImportListings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Listings from XML.';

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
     * @return mixed
     */
    public function handle()
    {
        $filename = 'listings.xml';
        $pics = array();
        $listing = array();
        $xml = XmlParser::load($filename);

        foreach($xml.getChildren() as $key => $listing) {
          $item = $listing->parse([
            'FullStreetAddress' => ['uses' => 'product::FullStreetAddress'],
            'City' => ['uses' => 'product::City'],
            'StateOrProvince' => ['uses' => 'product::StateOrProvince'],
            'PostalCode' => ['uses' => 'product::PostalCode'],
            'Country' => ['uses' => 'product::Country'],
            'ListPrice' => ['uses' => 'product::ListingURL'],
            'Bedrooms' => ['uses' => 'product::Bedrooms'],
            'Bathrooms' => ['uses' => 'product::Bathrooms'],
            'PropertyType' => ['uses' => 'product::PropertyType'],
            'ListingKey' => ['uses' => 'product::ListingKey'],
            'ListingCategory' => ['uses' => 'product::ListingCategory'],
            'ListingStatus' => ['uses' => 'product::ListingStatus'],
            'ListingDescription' => ['uses' => 'product::ListingDescription'],
            'MlsId' => ['uses' => 'product::MlsId'],
            'MlsName' => ['uses' => 'product::MlsName'],
            'MlsNumber' => ['uses' => 'product::MlsNumber'],
          ]);
        }
    }
}
