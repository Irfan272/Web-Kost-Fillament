<?php 

namespace App\Repositories;

use App\Interfaces\BoardingHouseRepositoryInterface;
use App\Models\BoardingHouse;
use DB;
use Filament\Forms\Components\Builder;

class BoardingHouseRepository implements BoardingHouseRepositoryInterface{

    public function getAllBoardingHouse($search = null, $city = null, $category = null){
        $query = BoardingHouse::query();

        // ketika search diisi maka dia akan dijalankan
        if($search){
            $query->where('name', 'like', '%'. $search . '%');
        }

        // ketika city diisi maka dia akan dijalankan untuk mencari slug city
        if($city){
            $query->whereHas('city', function (Builder $query) use ($city){
                $query->where('slug', $city);
            });
        }

          // ketika category diisi maka dia akan dijalankan untuk mencari slug category
        if($category){
            $query->whereHas('category', function (Builder $query) use ($category){
                $query->where('slug', $category);
            });
        }

        return $query->get();
    }


    public function getPopularBoardingHouses($limit = 5) {
        return DB::table('boarding_houses')
            ->select(
                'boarding_houses.*',
                'categories.name as category_name',
                'cities.name as city_name',
                DB::raw('(SELECT COUNT(*) FROM transactions WHERE transactions.boarding_house_id = boarding_houses.id) as transaction_count')
            )
            ->leftJoin('categories', 'boarding_houses.category_id', '=', 'categories.id')
            ->leftJoin('cities', 'boarding_houses.city_id', '=', 'cities.id')
            ->orderBy('transaction_count', 'desc')
            ->limit($limit)
            ->get();
    }
    

    public function getBoardingHouseByCitySlug($slug){
        return BoardingHouse::whereHas('city', function (Builder $query) use ($slug) {
            $query->where('slug', $slug);
        })->get();
    }

    public function getBoardingHouseByCategorySlug($slug){
        return BoardingHouse::whereHas('category', function (Builder $query) use ($slug) {
            $query->where('slug', $slug);
        })->get();
    }

    public function getBoardingHouseBySlug($slug){
        return BoardingHouse::where('slug', $slug)->first();
    }




}