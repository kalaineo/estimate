<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use Validator;
use Session;
use Auth;
use Exception;
use PDF;
use DB;
use Illuminate\Support\Facades\Mail;
use Hash;
use App\Quotation;
class EstimateController extends Controller
{
    
    public function __construct()
    {
        // common functions here...
    }

    public function Estimate(Request $request)
    {
        $listData = Quotation::all()->toArray();
        
        $locationData =DB::table('quotation')->select('location')->groupBy('location')->get()->toArray();
        $locationData = array_column($locationData,'location');
        $locationData = array_combine($locationData,$locationData);
        
        $hdtypeData =DB::table('quotation')->select('hard_disc_type')->groupBy('hard_disc_type')->get()->toArray();
        $hdtypeData = array_column($hdtypeData,'hard_disc_type');
        $hdtypeData = array_combine($hdtypeData,$hdtypeData);
        
        $hdsizeData =DB::table('quotation')->select('hard_disc_size')->groupBy('hard_disc_size')->get()->toArray();
        $hdsizeData = array_column($hdsizeData,'hard_disc_size');
        $hdsizeData = array_combine($hdsizeData,$hdsizeData);
        
        $ramsizeData =DB::table('quotation')->select('ram_size')->groupBy('ram_size')->get()->toArray();
        $ramsizeData = array_column($ramsizeData,'ram_size');
        $ramsizeData = array_combine($ramsizeData,$ramsizeData);
        
        $query = DB::table('quotation');
        if(isset($request['hard_disc_type']) && !empty($request['hard_disc_type']) && $request['hard_disc_type'] != 'undefined'){
            $query->where('hard_disc_type', '=', $request['hard_disc_type']);
        }
        if(isset($request['location']) && !empty($request['location']) && $request['location'] != 'undefined'){
            $query->where('location', '=', $request['location']);
        }
        if(isset($request['ram_size']) && $request['ram_size'] != 'undefined'){
            $query->whereIn('ram_size', explode(',',$request['ram_size']));
        }
        if(isset($request['hard_disc_size_min']) && !empty($request['hard_disc_size_min']) && $request['hard_disc_size_min'] != 'undefined'){
            
            $query->where(
                function($q) use ($request) {
                $q->whereBetween('hard_disc_size', array($request['hard_disc_size_min'],$request['hard_disc_size_max']))
                ->orWhere('hard_disc_unit', 'TB');
                });
        }
        
        $filteredData = $query->get()->toArray();
        
       // echo "<pre>";print_r($query->toSql());exit;
        
        $_Aresult = array();
        $_Aresult['listData']       = $listData;
        $_Aresult['locationData']   = $locationData;
        $_Aresult['hdtypeData']     = $hdtypeData;
        $_Aresult['ramsizeData']    = $ramsizeData;
        $_Aresult['hdsizeData']     = $hdsizeData;
        $_Aresult['filteredData']   = $filteredData;

        return json_encode($_Aresult);
    }
}
?>
