<?php

namespace App\Http\Controllers;

use DB;
use View;
Use Redirect;
use App\Bill;
use Session;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class BillController extends BaseController {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $bills = DB::table('bills')->orderBy('id', 'desc')->whereNull('deleted_at')->get();
        $view = View::make('bills.index');
        return $view->with('bills', $bills)->with('sums', $this->getSums())->with('teams', $this->getTeams());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('bills.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $rules = array(
            'team'      => 'required',
            'price'     => 'required|numeric',
            'currency'  => 'required',
            'description'  => 'required',
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('bills/create')
                ->withErrors($validator)
                ->withInput(Input::all());
        } else {
            // store
            $bill = new Bill;
            $bill->team       = Input::get('team');
            $bill->price      = Input::get('price');
            $bill->currency   = Input::get('currency');
            $bill->description = Input::get('description');

            if($bill->currency == 'kn') {
                $bill->price *= 0.13;
                $bill->currency = 'eur';
            }

            $bill->save();

            // redirect
            Session::flash('message', 'Uspješno dodan trošak!');
            return Redirect::to('bills');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $bill = Bill::find($id);

        return View::make('bills.edit')->with('bill', $bill);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $rules = array(
            'team'      => 'required',
            'price'     => 'required|numeric',
            'currency'  => 'required',
            'description'  => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        // process the login
        if ($validator->fails()) {
            return Redirect::to('bills/' . $id . '/edit')
                ->withErrors($validator)
                ->withInput(Input::all());
        } else {
            // store
            $bill = Bill::find($id);
            $bill->team       = Input::get('team');
            $bill->price      = Input::get('price');
            $bill->currency   = Input::get('currency');
            $bill->description = Input::get('description');
            $bill->save();

            // redirect
            Session::flash('message', 'Trošak uspješno ažuriran!');
            return Redirect::to('bills');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        // delete
        $bill = Bill::find($id);
        $bill->delete();

        // redirect
        Session::flash('message', 'Stavka uspješno izbrisana!');
        return Redirect::to('bills');
    }

    private function getTeams(){
        return array('GE' => 'Gerenčiri', 'GL' => 'Gluhaki');
    }
    private function getSums(){
        $count['GE'] = DB::table('bills')->whereNull('deleted_at')->select('team', DB::raw('SUM(price) as total_prices'))->groupBy('team')->where('team','GE')->first();
        $count['GL'] = DB::table('bills')->whereNull('deleted_at')->select('team', DB::raw('SUM(price) as total_prices'))->groupBy('team')->where('team','GL')->first();

        $count['GE'] = $count['GE']->total_prices;
        $count['GL'] = $count['GL']->total_prices;
        $count['ALL'] = $count['GE'] + $count['GL'];

        if($count['GE'] > $count['GL']){
            $dif = ( $count['GE'] - $count['GL'] ) / 2;
            $count['GE_DIF'] = $dif;
            $count['GL_DIF'] = $dif * -1;
            $count['GE_LABEL'] = 'success';
            $count['GL_LABEL'] = 'danger';
            $count['GE_NAME'] = 'Vode';
            $count['GL_NAME'] = 'Gube';
        }else if( $count['GE'] < $count['GL'] ){
            $dif = ( $count['GL'] - $count['GE'] ) / 2;
            $count['GL_DIF'] = $dif;
            $count['GE_DIF'] = $dif * -1;
            $count['GL_LABEL'] = 'success';
            $count['GE_LABEL'] = 'danger';
            $count['GL_NAME'] = 'Vode';
            $count['GE_NAME'] = 'Gube';
        }else{
            $count['GL_DIF'] = 0;
            $count['GE_DIF'] = 0;
            $count['GE_LABEL'] = 'success';
            $count['GL_LABEL'] = 'success';
            $count['GL_NAME'] = 'Razlika';
            $count['GE_NAME'] = 'Razlika';
        }

        return $count;
    }
}
