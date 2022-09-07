<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Certificate;
 use App\Traits\GeneralTrait;
use Validator;

use DB;

class CertificatesController extends Controller
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

   // Return all certificates
    public function index()
    {
       try {
        
          $certificates = Certificate::orderBy('created_at')->get();
        return  $this-> returnData('certificates',$certificates);

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


   // Create a certificate
    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(),[            
                     "stream_name" => "required", 
                     "property_id" => "required|exists:properties,id", 
                     "issue_date" => "required|date", 
                     "next_due_date" => "required|date", 
   
                ]);
            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }
            DB::beginTransaction();
            $certificate = new Certificate();

            $certificate->stream_name =$request->input('stream_name'); 
            $certificate->property_id =$request->input('property_id');  
            $certificate->issue_date =$request->input('issue_date');  
            $certificate->next_due_date =$request->input('next_due_date');     
       
            $certificate->save();

            DB::commit();
            return $this->returnSuccessMessage('Certificate saved successfully');
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

   // Return a certificate
    public function show($id)
    {
        try {
            $certificate = Certificate::where('id',$id)->first();
          return $this-> returnData('certificate',$certificate);
  
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
    public function update($id)
    {}
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {}


    //Returns the notes of a certificate
    public function certificateNotes($id)
    {
        try {
            $certificate = Certificate::findorFail($id);

             if (!$certificate) {
                        return $this->returnError('D000', trans('This certificate does not exist'));
             }
       
             $notes = $certificate->notes;
             return  $this-> returnData('notes',$notes);
         } catch (\Exception $ex) {
                return $this->returnError($ex->getCode(), $ex->getMessage());
         }
        
    }



    //Create a note for a certificate 
     public function storeCertificate(Request $request , $id)
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
            
            $certificate = Certificate::findorFail($id);
             $certificate->notes()->create([
                'note' =>$request->input('note'),
             ]);

            DB::commit();
            return $this->returnSuccessMessage('Note for certificate saved successfully');
            }catch (\Exception $ex) {
                DB::rollback();
                return $this->returnError($ex->getCode(), $ex->getMessage());
            }
        }

}







