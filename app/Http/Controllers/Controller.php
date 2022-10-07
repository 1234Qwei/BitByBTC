<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use App\Models\ExchangeDeposit;
use App\Models\ExchangeSwap;

use App\Models\FeesForSwapWithdraw;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendSMS($customMessage)
    {
        // Account details
        $apiKey = urlencode('NzQ2OTc0NzUzNjZmNzM3MzY5NTY3NDY5NmEzODZjNzk=');

        // Message details
        $numbers = array(919629093258);
        $sender = urlencode('600010');
        $message = rawurlencode($customMessage);

        $numbers = implode(',', $numbers);

        // Prepare data for POST request
        $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);

        // Send the POST request with cURL
        $ch = curl_init('https://api.textlocal.in/send/');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        dd($response);
        // Process your response here
        return $response;
    }

    public function uploadFiles($request, $attachments, $fileExtensions, $is_ajax = 0)
    {
        $fileName = $_FILES[$attachments]['name'];
        $explode = explode('.', $fileName);
        $extension = end($explode);
        $fileExtension = strtolower($extension);
        $mimeImage = mime_content_type($_FILES[$attachments]['tmp_name']);
        $explode = explode('/', $mimeImage);

        if (!in_array($fileExtension, $fileExtensions)) {
            if ($is_ajax) {
                return 0;
            }
            session()->flash('error', 'Invalid file type. Only image files are accepted.');
            return redirect()->back();
        } else {
            $storagePath = Storage::disk('s3')->put(env('AWS_BUCKET'), $request, 'public');
            $image  = Storage::disk('s3')->url($storagePath);
            if ($image) {
                return $image;
            } else {
                if ($is_ajax) {
                    return 0;
                }
                session()->flash('error', 'file not update');
                return redirect()->back();
            }
        }
    }
    public function sendEmail($id, $exchange_type)
    {
        $exchange = ($exchange_type == '1') ? ExchangeDeposit::find($id) : ExchangeSwap::find($id);
        $data = [];
        if ($exchange->exchange_type == 2) {
            $data = [
                'user' => $exchange->user->name . '( ' . $exchange->user->username . ')',
                'coin' => $exchange->swap_from_coin . ' ' . $exchange->swapFrom->symbol,
                'swap_coin' => $exchange->swap_to_coin . ' ' . $exchange->swapTo->symbol,
                'remarks' => $exchange->remarks,
                'created_at' => $exchange->created_at,
                'approver_comment' => $exchange->approver_comment,
                'approved_date' => $exchange->approved_date,
                'email' => $exchange->user->email,
                'status' => $exchange->status
            ];
            $data['swap_fee'] = $exchange->swap_fee . ' ' . $exchange->swapFrom->symbol;
            $template = 'emails.swap';
            $data['subject'] = '【ANYSWAP】Request for swap';
        } else {
            $data = [
                'user' => $exchange->user->name . '( ' . $exchange->user->username . ')',
                'coin' => $exchange->deposit_coin,
                'created_at' => $exchange->created_at,
                'approver_comment' => $exchange->approver_comment,
                'approved_date' => $exchange->approved_date,
                'email' => $exchange->user->email,
                'status' => $exchange->status
            ];

            $template = 'emails.deposit';
            $data['subject'] = '【ANYSWAP】Request for deposit';

            // $data = "Hello Sathish, New deposit posted by {$exchange->user->username} for the value of {$exchange->deposit_coin} {$exchange->deposit->symbol}. Regards, Team Anyswap";
            // $this->sendSMS($data);
        }

        \Mail::send($template, $data, function ($message) use ($data) {
            $message->from(env('MAIL_FROM_ADDRESS'), 'AnySwap');
            $message->subject($data['subject']);
            $message->to(strtolower($data['email']));
            $message->bcc(['kcelaxman@gmail.com']);
        });
    }
}
