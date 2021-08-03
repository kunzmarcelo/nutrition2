<?php

namespace App\Http\Controllers\Painel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserFormRequest;
use App\Delivery;
use App\Reproduction;
use App\Animal;
use App\User;
use App\Role;
use App\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;


class UserController extends Controller
{
  private $user;

  public function __construct(User $user) {
      $this->user = $user;
      $this->middleware('auth');
  }

  public function index() {


    // $level = auth()->user()->level;
    // dd($level);
    //
    //   $results = $this->user->where()->get();
      $results = $this->user->all();
      //dd($results);

      return view('painel.users.index', compact('results'));
  }

  public function roles($id)
  {
     $user = $this->user->find($id);
     //recuperar roles
     $roles = $user->roles()->get();
     //dd($roles);
       return view('painel.users.roles', compact('user','roles'));
  }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      $now = Carbon::now()->format('m');
      $last_3_months = ($now - 3);
      $last_month = ($now - 1);
      $iterable = $this->user->find($id);
      $animalsTotal = Animal::where('user_id',$id)->count();
      $mediaDel = Reproduction::where('user_id',$id)->avg('del');

      $production_last_3_months = Delivery::where('user_id',$id)->whereMonth('collection_date', '=', $last_3_months )->sum('total_liters_produced'); //Produção ultimo 3 mêses
      $production_last_month = Delivery::where('user_id',$id)->whereMonth('collection_date', '=', $last_month )->sum('total_liters_produced'); //Produção último mês
      $production_current_month = Delivery::where('user_id',$id)->whereMonth('collection_date', '=', $now)->sum('total_liters_produced'); //Produção mês atual




        return view('painel.users.show',compact('iterable','animalsTotal','mediaDel','production_last_3_months','production_last_month','production_current_month'));
    }

    public function changeStatus(Request $request)
    {

      $user = User::findOrFail($request->user_id);
      $user->status = $request->status;
      $user->save();

    return response()->json(['message' => 'User status updated successfully.']);
    }

    public function edit($id)
    {


        return view('management.users.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserFormRequest $request)
    {
      $data = $request->all();
      if($data['password']!=null){
        $data['password'] = bcrypt($data['password']);
      }else {
        unset($data['password']);
      }

      $update = auth()->user()->update($data);

      if($update){
        return redirect()->back()->with('success', 'Dados alterados com sucesso');

      }
        else {
          return redirect()->back()->with('error', 'Falha ao alterar os registros');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
