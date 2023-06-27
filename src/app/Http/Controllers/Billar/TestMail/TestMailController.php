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

        // try {
        //     var_dump(Mail::to($request->email)
        //         ->send(new TestMail($request->subject, $request->message)));
        //     return response()->json(['status' => true, 'message' => __('email_sent_successfully')]);
        // } catch (\Exception $exception) {
        //     return response()->json(['status' => false, 'message' => __('email_setup_is_not_correct')]);
        // }


        try {
            $mail = new TestMail($request->subject, $request->message);
            Mail::to($request->email)->send($mail);

            if (count(Mail::failures()) > 0) {
                // Email failed to send to one or more recipients
                return response()->json(['status' => false, 'message' => Mail::failures()]);
            } else {
                // Email sent successfully
                return response()->json(['status' => true, 'message' => __('email_sent_successfully')]);
            }
        } catch (\Exception $exception) {
            // Exception occurred while sending email
            return response()->json(['status' => false, 'message' => __('email_setup_is_not_correct')]);
        }
    }
}
