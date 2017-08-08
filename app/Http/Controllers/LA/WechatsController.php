<?php
/**
 * Controller generated using LaraAdmin
 * Help: http://laraadmin.com
 * LaraAdmin is open-sourced software licensed under the MIT license.
 * Developed by: Dwij IT Solutions
 * Developer Website: http://dwijitsolutions.com
 */

namespace App\Http\Controllers\LA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use DB;
use Validator;
use Datatables;
use Collective\Html\FormFacade as Form;
use Dwij\Laraadmin\Models\Module;
use Dwij\Laraadmin\Models\ModuleFields;

use EasyWeChat\Foundation\Application;

use App\Models\Wechat;

class WechatsController extends Controller
{
    public $show_action = true;

    public function __construct(){
        //$this->middleware('auth', ['only' => 'valid']);
        //$this->middleware('auth', ['except' => 'valid']);
    }
    
    /**
     * Display a listing of the Wechats.
     *
     * @return mixed
     */
    public function index()
    {
        $module = Module::get('Wechats');
        
        if(Module::hasAccess($module->id)) {
            return View('la.wechats.index', [
                'show_actions' => $this->show_action,
                'listing_cols' => Module::getListingColumns('Wechats'),
                'module' => $module
            ]);
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for creating a new wechat.
     *
     * @return mixed
     */
    public function create()
    {
        //
        echo "aaa";
    }
    
    /**
     * Store a newly created wechat in database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if(Module::hasAccess("Wechats", "create")) {
            
            $rules = Module::validateRules("Wechats", $request);
            
            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $insert_id = Module::insert("Wechats", $request);
            
            return redirect()->route(config('laraadmin.adminRoute') . '.wechats.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Display the specified wechat.
     *
     * @param int $id wechat ID
     * @return mixed
     */
    public function show($id)
    {
        if(Module::hasAccess("Wechats", "view")) {
            
            $wechat = Wechat::find($id);
            if(isset($wechat->id)) {
                $module = Module::get('Wechats');
                $module->row = $wechat;
                
                return view('la.wechats.show', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                    'no_header' => true,
                    'no_padding' => "no-padding"
                ])->with('wechat', $wechat);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("wechat"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Show the form for editing the specified wechat.
     *
     * @param int $id wechat ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if(Module::hasAccess("Wechats", "edit")) {
            $wechat = Wechat::find($id);
            if(isset($wechat->id)) {
                $module = Module::get('Wechats');
                
                $module->row = $wechat;
                
                return view('la.wechats.edit', [
                    'module' => $module,
                    'view_col' => $module->view_col,
                ])->with('wechat', $wechat);
            } else {
                return view('errors.404', [
                    'record_id' => $id,
                    'record_name' => ucfirst("wechat"),
                ]);
            }
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Update the specified wechat in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id wechat ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if(Module::hasAccess("Wechats", "edit")) {
            
            $rules = Module::validateRules("Wechats", $request, true);
            
            $validator = Validator::make($request->all(), $rules);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();;
            }
            
            $insert_id = Module::updateRow("Wechats", $request, $id);
            
            return redirect()->route(config('laraadmin.adminRoute') . '.wechats.index');
            
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Remove the specified wechat from storage.
     *
     * @param int $id wechat ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        if(Module::hasAccess("Wechats", "delete")) {
            Wechat::find($id)->delete();
            
            // Redirecting to index() method
            return redirect()->route(config('laraadmin.adminRoute') . '.wechats.index');
        } else {
            return redirect(config('laraadmin.adminRoute') . "/");
        }
    }
    
    /**
     * Server side Datatable fetch via Ajax
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function dtajax(Request $request)
    {
        $module = Module::get('Wechats');
        $listing_cols = Module::getListingColumns('Wechats');
        
        $values = DB::table('wechats')->select($listing_cols)->whereNull('deleted_at');
        $out = Datatables::of($values)->make();
        $data = $out->getData();
        
        $fields_popup = ModuleFields::getModuleFields('Wechats');
        
        for($i = 0; $i < count($data->data); $i++) {
            for($j = 0; $j < count($listing_cols); $j++) {
                $col = $listing_cols[$j];
                if($fields_popup[$col] != null && starts_with($fields_popup[$col]->popup_vals, "@")) {
                    $data->data[$i][$j] = ModuleFields::getFieldValue($fields_popup[$col], $data->data[$i][$j]);
                }
                if($col == $module->view_col) {
                    $data->data[$i][$j] = '<a href="' . url(config('laraadmin.adminRoute') . '/wechats/' . $data->data[$i][0]) . '">' . $data->data[$i][$j] . '</a>';
                }
                // else if($col == "author") {
                //    $data->data[$i][$j];
                // }
            }
            
            if($this->show_action) {
                $output = '';
                if(Module::hasAccess("Wechats", "edit")) {
                    $output .= '<a href="' . url(config('laraadmin.adminRoute') . '/wechats/' . $data->data[$i][0] . '/edit') . '" class="btn btn-warning btn-xs" style="display:inline;padding:2px 5px 3px 5px;"><i class="fa fa-edit"></i></a>';
                }
                
                if(Module::hasAccess("Wechats", "delete")) {
                    $output .= Form::open(['route' => [config('laraadmin.adminRoute') . '.wechats.destroy', $data->data[$i][0]], 'method' => 'delete', 'style' => 'display:inline']);
                    $output .= ' <button class="btn btn-danger btn-xs" type="submit"><i class="fa fa-times"></i></button>';
                    $output .= Form::close();
                }
                $data->data[$i][] = (string)$output;
            }
        }
        $out->setData($data);
        return $out;
    }
}
