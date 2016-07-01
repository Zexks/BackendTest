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
        $xml = XmlParser::load($filename);

        foreach($xml.getChildren() as $key => $listing) {
          $listing->parse([
            $proptype = listing::PropertyType;
            $cat = listing::ListingCategory;
            $mls = listing::MlsId;

            if (!PropType::where('code', '=', $proptype)->count() > 0) {
              DB::table('proptype')->insert([
                'code' => $proptype,
                'description' => listing::PropertyType
              ]);
            }

            if (!Category::where('code', '=', $cat)->count() > 0) {
              DB::table('proptype')->insert([
                'code' => $proptype,
                'description' => listing::PropertyType
              ]);
            }

            if (!MLS::where('code', '=', $mls)->count() > 0) {
              DB::table('mls')->insert([
                'code' => $mls,
                'Description' => listing::MlsName
              ]);
            }

            DB::table('listing')->insert([
              'street' => $listing::FullStreetAddress,
              'city' => $listing::City,
              'state' => $listing::StateOrProvince,
              'zip' => $listing::PostalCode,
              'country' => $listing::Country,
              'price' => $listing::ListingURL,
              'bed' => $listing::Bedrooms,
              'bath' => $listing::Bathrooms,
              'proptype' => DB::select('proptype')->where('code', '=', $proptype)->pluck('id'),
              'key' => $listing::ListingKey,
              'category' => DB::select('category')->where('code', '=', $cat)->pluck('id'),
              'status' => $listing::ListingStatus,
              'description' => $listing::ListingDescription,
              'mlsid' => DB::select('mls')->where('code', '=', $mls)->pluck('id'),
              'mlsnum' => $listing::MlsNumber
            ]);

            $pics = $listing::Photos;

            foreach($pics as $pic) {
              $picture->parse([
                DB::table('photos')->insert([
                  'timestamp' => $picture::MediaModificationTimestamp,
                  'url' => $picture::MediaURL
                ]);
              ]);
            }
          ]);
        }
    }
}
