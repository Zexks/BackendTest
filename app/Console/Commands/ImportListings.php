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
        $filename = 'Listings.xml';
        $pics = array();
        $xml = XmlParser::load($filename);
        $simplexml = simplexml_load_file($filename);

        foreach($simplexml->post as $key => $value) {
          $listing = simplexml_load_string($value->listing);

          $proptype = $listing->PropertyType;
          $cat = $listing->ListingCategory;
          $mls = $listing->MlsId;

          if (!PropType::where('code', '=', $proptype)->count() > 0) {
            DB::table('proptype')->insert([
              'code' => $proptype,
              'description' => $proptype
            ]);
          }

          if (!Category::where('code', '=', $cat)->count() > 0) {
            DB::table('proptype')->insert([
              'code' => $cat,
              'description' => $listing->$cat,
            ]);
          }

          if (!MLS::where('code', '=', $mls)->count() > 0) {
            DB::table('mls')->insert([
              'code' => $mls,
              'Description' => $listing->MlsName,
            ]);
          }

          DB::table('listing')->insert([
            'street' => $listing->FullStreetAddress,
            'city' => $listing->City,
            'state' => $listing->StateOrProvince,
            'zip' => $listing->PostalCode,
            'country' => $listing->Country,
            'price' => $listing->ListingURL,
            'bed' => $listing->Bedrooms,
            'bath' => $listing->Bathrooms,
            'proptype' => DB::select('proptype')->where('code', '=', $proptype)->pluck('id'),
            'key' => $listing->ListingKey,
            'category' => DB::select('category')->where('code', '=', $cat)->pluck('id'),
            'status' => $listing->ListingStatus,
            'description' => $listing->ListingDescription,
            'mlsid' => DB::select('mls')->where('code', '=', $mls)->pluck('id'),
            'mlsnum' => $listing->MlsNumber,
          ]);

          $pics = simplexml_load_string($listing->Photos);

          foreach($pics as $pic) {
            DB::table('photos')->insert([
              'listing_id' => DB::table('listings')->where('street', $listing->FullStreetAddress)->value('id'),
              'timestamp' => $pic::MediaModificationTimestamp,
              'url' => $pic::MediaURL,
            ]);
          }
        }
    }
}
