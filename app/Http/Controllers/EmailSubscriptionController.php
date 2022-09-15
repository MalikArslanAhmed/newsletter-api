<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Response as R;
use App\Models\EmailSubscription;
use Validator, DB;

class EmailSubscriptionController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function AddEmail()
    {
        $inputs = $this->request->all();
        $v = Validator::make($inputs, [
            'email'    =>  'required',
        ]);
        if ($v->fails()) {
            return R::ValidationError($v->errors());
        }
        if (EmailSubscription::where('email', $inputs['email'])->exists()) {
            return R::SimpleError('This Email is already regsitered for newsletter');
        }
        $email_data = [
            'email'     =>   @$inputs['email'],
        ];
        DB::beginTransaction();
        try {
            $add_data =  EmailSubscription::create($email_data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return $e;
        }
        return R::Success('Email Added as recipient for newsletter', $add_data);
    }

    public function  DeleteEmail()
    {
        $inputs = $this->request->all();
        $v = Validator::make($inputs, [
            'email'    =>  'required',
        ]);
        if ($v->fails()) {
            return R::ValidationError($v->errors());
        }
        if (!EmailSubscription::where('email', $inputs['email'])->exists()) {
            return R::SimpleError('This Email doesnot registered with newsletter recipient');
        } else {
            EmailSubscription::where('email', $inputs['email'])->delete();
            return R::Success('Email deleted from subscription');
        }
    }
}
