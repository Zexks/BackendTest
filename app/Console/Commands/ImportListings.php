<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Listing;
use App\Models\MLS;
use App\Models\Photo;
use App\Models\PropType;
use XmlParser;
use Log;
use DB;
use Illuminate\Console\Command;

class ImportListings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:listings';

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
      $filename = public_path().'\\listings.xml';
      $simplexml = simplexml_load_file($filename);

      foreach($simplexml->Listing as $listing) {
        $namespaces = $listing->Address->getNameSpaces(true);

        $proptype = $listing->PropertyType;
        Log::info('PropType check: '.$proptype);
        if (!PropType::where('description', $proptype)->count() > 0) {
          DB::table('proptypes')->insert([
            'code' => (DB::table('proptypes')->max('code') + 1),
            'description' => $proptype
          ]);
        }

        $category = $listing->ListingCategory;
        Log::info('Category check: '.$category);
        if (!Category::where('description', $category)->count() > 0) {
          DB::table('categories')->insert([
            'code' => (DB::table('categories')->max('code') + 1),
            'description' => $category
          ]);
        }

        $mlsnum = $listing->MlsNumber;
        $mlsid = $listing->MlsId;
        Log::info('MLS check: '.$mlsid);
        if (!MLS::where('code', $mlsid)->count() > 0) {
          DB::table('mls')->insert([
            'code' => $mlsid,
            'name' => $listing->MlsName,
          ]);
        }

        $street = $listing->Address->children($namespaces['commons'])->FullStreetAddress;
        Log::info('Inserting Listing: '.$street);
        if (!Listing::where('street', $street)->count() > 0) {
          DB::table('listings')->insert([
            'street' => $street,
            'city' => $listing->Address->children($namespaces['commons'])->City,
            'state' => $listing->Address->children($namespaces['commons'])->StateOrProvince,
            'zip' => $listing->Address->children($namespaces['commons'])->PostalCode,
            'country' => $listing->Address->children($namespaces['commons'])->Country,
            'price' => $listing->ListPrice,
            'url' => $listing->ListingURL,
            'bed' => $listing->Bedrooms,
            'bath' => $listing->Bathrooms,
            'key' => $listing->ListingKey,
            'status' => $listing->ListingStatus,
            'description' => $listing->ListingDescription,
            'mlsnumber' => $mlsnum,
            'mlsid' => MLS::where('code', $mlsid)->pluck('id')[0],
            'categoryid' => Category::where('description', $category)->pluck('id')[0],
            'proptypeid' => PropType::where('description', $proptype)->pluck('id')[0]
          ]);
        }

        $ttlpic = $listing->Photos->children()->count();
        Log::info('Inserting pictures: '.$ttlpic);
        if($ttlpic > 0) {
          foreach($listing->Photos->children() as $pic) {
            DB::table('photos')->insert([
              'listing_id' => Listing::where('street', $street)->pluck('id')[0],
              'timestamp' => $pic->MediaModificationTimestamp,
              'url' => $pic->MediaURL,
            ]);
          }
        }
      }
    }
}
