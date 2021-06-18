<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Animal;
use App\Medicine;
use Illuminate\Http\Request;
use \App\AnimalMedicine;
use Carbon\Carbon;

class AnimalMedicineController extends Controller
{
  private $medicine;
  public function __construct(AnimalMedicine $medicine){
    $this->medicine = $medicine;
      $this->middleware('auth');
  }
    public function index()
    {
        $now = Carbon::now()->format('y-m-d');

      $results= $this->medicine->where('next_application','>=',$now)->get();
      //dd($results);
        return view('painel.animalMedicine.index', compact('results'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $animals = Animal::where('active','=','Sim')->get();
      $medicines = Medicine::all();
      return view('painel.animalMedicine.create', compact('animals','medicines'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $animals = $request->input('animals_id');


        if ( $animals ) {
            foreach ( $animals as $id) {
                $medicine_id=  $request->input('medicine_id');
                $application_date=  $request->input('application_date');
                $next_application=  $request->input('next_application');


                $insert = $this->medicine->create([
                    'animal_id' => $id,
                    'medicine_id' => $medicine_id,
                    'application_date' => $application_date,
                    'next_application' => $next_application,
                  ]);
              }
              if ($insert) {
                alert()->success('Registro inserido!','Sucesso')->persistent('Fechar')->autoclose(1800);
                         return redirect()->back();
                     }
                     alert()->error('Ocorreu um erro por favor tente novamente mais tarde!','Woops')->persistent('Fechar')->autoclose(1800);
                     return back();
          }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $dado = $this->medicine->find($id);

        $detele = $dado->delete();


      if ($detele){
        return response()->json([
                'success' => 'Registro deletado com sucesso!'
            ]);
          }else{
            return response()->json([
                    'success' => 'Ocorreu um erro por favor tente novamente mais tarde!'
                ]);
          }


    }
}
