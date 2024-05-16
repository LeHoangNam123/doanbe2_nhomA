<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Orderitem;
use Mail;
class OrderController extends Controller
{
   
    // cập nhật trạng thái đơn hàng
    public function approveOrder($order_id){
        $order = Order::find($order_id);
        if(!$order){
            return redirect()->back();
        }
        if($order->status !== Order::STATUS_PROCESSING){
            return redirect()->back();
        }
        $order->status = Order::STATUS_APPROVED;
        $order->save();
        // Gửi mail cho user đó khi admin đã nhận đơn hàng
        $name = $order->receiver;
        $email_receiver = $order->user->email; // mail người nhận
        Mail::send('emails.body', compact('name'), function($email) use ($email_receiver, $name) {
            $email->subject('Thư giới thiệu');
            $email->to($email_receiver, $name);
        });
        return redirect()->back();
    }
}
