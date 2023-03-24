<?php

namespace App\Http\Controllers\Billar\TestMail;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Mail\Billar\TestMail\TestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestMailController extends Controller
{
    public function testMailSend(Request $request)
    {
        $request->validate([
            'email' => ['required'],
            'subject' => ['required'],
            'message' => ['required'],
        ]);

        try {
            Mail::to($request->email)
                ->send(new TestMail($request->subject, $request->message));
            return response(['status' => true, 'message' => __t('email_sent_successfully')]);
        } catch (\Exception $exception) {
            return response(['status' => false, 'message' => __t('email_setup_is_not_correct')]);
        }
    }
}
