<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Note;
use App\Models\Certificate;
use App\Traits\GeneralTrait;
use Validator;

use DB;

class PropertiesController extends Controller
{

    use GeneralTrait;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     //Return all properties
    public function index()
    {
    try {
        
          $properties = Property::orderBy('created_at')->get();
          return  $this-> returnData('properties',$properties);

        } catch (\Exception $ex) {
          return $this->returnError($ex->getCode(), $ex->getMessage());
     }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


   // Create a new property
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[            
                     "organisation" => "required", 
                     "property_type" => "required", 
                     "uprn" => "required|integer", 
                     "address" => "required", 
                     "postcode" => "required", 
                     "live" => "required", 
                ]);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            DB::beginTransaction();
            $property = new Property();

            if (!$request->has('live'))
            $property->live = 0; 
            else
            $property->live = 1; 
             
            $property->organisation =$request->input('organisation'); 
            $property->property_type =$request->input('property_type');  
            $property->parent_property_id =$request->input('parent_property_id');  
            $property->uprn =$request->input('uprn');     
            $property->address =$request->input('address'); 
            $property->town =$request->input('town');  
            $property->postcode =$request->input('postcode');  
 
            $property->save();

            DB::commit();
            return $this->returnSuccessMessage('Property saved successfully');
            }catch (\Exception $ex) {
                DB::rollback();
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
        }
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */

    //Return a property 
    public function show($id)
    {
        try {
            $property = Property::where('id',$id)->first();
          return  $this-> returnData('property',$property);
  
          } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
       }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //Update a property

    public function update( $id,Request $request)
    {
       try {
           DB::beginTransaction();
          $validator = Validator::make($request->all(), [
            "organisation" => "required", 
            "property_type" => "required", 
            "uprn" => "required|integer", 
            "address" => "required", 
            "postcode" => "required", 
            "live" => "required", 
                
            ]);
         if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
            $property = Property::find($id);
     
            if (!$property)
                 return $this->returnError('D000', 'This property does not exist');


            if (!$request->has('live'))
                $request->request->add(['live' => 0]);
            else
                $request->request->add(['live' => 1]);

            $property->organisation =$request->input('organisation'); 
            $property->property_type =$request->input('property_type');  
            $property->parent_property_id =$request->input('parent_property_id');  
            $property->uprn =$request->input('uprn');     
            $property->address =$request->input('address'); 
            $property->town =$request->input('town');  
            $property->postcode =$request->input('postcode');  
         
           
             $property->update();
            DB::commit();
            return $this->returnSuccessMessage('Property updated successfully');
       } catch (\Exception $ex) {
            DB::rollback();
          return $this->returnError($ex->getCode(), $ex->getMessage());
       }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //Delete a property done
    public function destroy($id)
    {
       try {
            $property = Property::find($id);
             if (!$property) {
                        return $this->returnError('D000', trans('This property does not exist'));
             }
       
             $property->delete();
           return $this->returnSuccessMessage('Property deleted successfully');
         } catch (\Exception $ex) {
                return $this->returnError($ex->getCode(), $ex->getMessage());
         }
    }


   //Return the certificates of a property
    public function getCertificates($id)
    {
        try {
            
            $property = Property::find($id);
             if (!$property) {
                        return $this->returnError('D000', trans('This property does not exist'));
             }
       
             $certificates = $property->certificates;
             return  $this-> returnData('certificates',$certificates);
         } catch (\Exception $ex) {
                return $this->returnError($ex->getCode(), $ex->getMessage());
         }

         
    }

    //Return the notes of a property

    public function propertyNotes($id)
    {
        try {
            $property = Property::findorFail($id);

             if (!$property) {
                        return $this->returnError('D000', trans('This property does not exist'));
             }
       
             $notes = $property->notes;
             return  $this-> returnData('notes',$notes);
         } catch (\Exception $ex) {
                return $this->returnError($ex->getCode(), $ex->getMessage());
         }      
    }



    
    public function storeNoteProperty(Request $request , $id)
    {
        try {
            $validator = Validator::make($request->all(),[            
                     "note" => "required", 
                ]);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            DB::beginTransaction();
            
            $property = Property::findorFail($id);
             $property->notes()->create([
                'note' =>$request->input('note'),
             ]);

            DB::commit();
            return $this->returnSuccessMessage('note saved successfully');
            }catch (\Exception $ex) {
                DB::rollback();
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
        }


        //get properties which has more than 5 certificates
        public function getProperties(){
            try {
        
                $properties = Property::has('certificates','>',5)->withCount('certificates')->get();
 
              return  $this-> returnData('properties',$properties);
      
              } catch (\Exception $ex) {
                return $this->returnError($ex->getCode(), $ex->getMessage());
           }
        
        }

}







