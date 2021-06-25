<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Animal;
use App\Delivery;
use App\Production;
use App\Reproduction;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;
use Carbon\Carbon;
class HomeController extends Controller
{

  public function __construct(){

      $this->middleware('auth');
  }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

          //dd('pagina do admin');
          $now = Carbon::now()->format('m');

          // /$results = $this->delivery->whereMonth('collection_date', '=', $now)->get();
          $results = Delivery::whereMonth('collection_date', '=', $now)->where('user_id','=',auth()->user()->id)->get();

          $animalsActive = Animal::where('active','=','sim')->where('user_id','=',auth()->user()->id)->count();
          $animalsTotal = Animal::where('user_id','=',auth()->user()->id)->count();
          $productionTotal = Delivery::whereMonth('collection_date', '=', $now)->where('user_id','=',auth()->user()->id)->sum('total_liters_produced');
          $mediaDel = Reproduction::where('user_id','=',auth()->user()->id)->avg('del');

            return view('home',compact('results','animalsActive','animalsTotal','productionTotal','mediaDel'));


        // if(Gate::denies('produtor')){
        //
        //     $now = Carbon::now()->format('m');
        //
        //     // /$results = $this->delivery->whereMonth('collection_date', '=', $now)->get();
        //     $results = $this->delivery->where('user_id','=',auth()->user()->id)->whereMonth('collection_date', '=', $now)->get();
        //
        //     $animalsActive = Animal::where('active','=','sim')->where('user_id','=',auth()->user()->id)->count();
        //     $animalsTotal = Animal::where('user_id','=',auth()->user()->id)->count();
        //     $productionTotal = Delivery::whereMonth('collection_date', '=', $now)->where('user_id','=',auth()->user()->id)->sum('total_liters_produced');
        //
        //       return view('home',compact('results','animalsActive','animalsTotal','productionTotal'));
        //     }
    }
}
