<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Response as R;
use Validator, DB, Mail;
use App\Models\Newsletter;
use App\Models\EmailSubscription;

class NewsletterController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function AddNewsletter()
    {
        $inputs = $this->request->all();
        $v = Validator::make($inputs, [
            'name'    =>  'required',
        ]);
        if ($v->fails()) {
            return R::ValidationError($v->errors());
        }
        $letter_data = [
            'name'     =>   @$inputs['name'],
        ];
        DB::beginTransaction();
        try {
            $add_data =  Newsletter::create($letter_data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return $e;
        }
        $list = EmailSubscription::get();
        foreach ($list as $value) {
            $this->html_email($value);
        }
        return R::Success('Newsletter dispatched successfully', $add_data);
    }
    public function html_email($value)
    {
        $value = array('email' => $value['email']);
        Mail::send('mail', $value, function ($message) use ($value) {
            $message->to($value['email'], 'Newsletter')->subject('Newsletter Arrived');
            $message->from('malikzarslan44@gmail.com', 'Newsletter');
        });
    }

    public function NewsletterList()
    {
        $list = Newsletter::get();
        return R::Success('data', $list);
    }

    public function DeleteNewsletter()
    {
        $inputs = $this->request->all();
        $v = Validator::make($inputs, [
            'id' => 'required|integer',
        ]);
        if ($v->fails()) {
            return R::ValidationError($v->errors());
        }
        $record = Newsletter::find($inputs['id']);
        if ($record) {
            $record->delete();
            return R::Success('Deleted successfully');
        } else {
            return R::SimpleError('cannot find record with this newsletter Id');
        }
    }
}
