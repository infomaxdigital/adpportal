<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingModel;

class PaymentController extends Controller
{
    //
    // public function Payment(Request $request){
    //     $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));
    //     $response = $stripe->checkout->sessions->create([
    //             'line_items' => [
    //                 [
    //                     'price_data' => [
    //                         'currency' => 'aud',
    //                         'product_data' => [
    //                             'name' => 'test',
    //                         ],
    //                         'unit_amount' => 50,
    //                     ],
    //                     'quantity' => 1,
    //                 ],
    //             ],

    //             'mode' => 'payment',
    //             'success_url' => route('success').'?session_id={CHECKOUT_SESSION_ID}',
    //             'cancel_url' => route('cancel'),
    //         ]);
    //         //dd($response);
    //         if(isset($response->id) && $response->id != ''){
    //             session()->put('product_name', 'test');
    //             session()->put('quantity', 1);
    //             session()->put('price', 50);
    //             return redirect($response->url);
    //         }
    //         else{
    //             return redirect()->route('cancel');
    //         }
    // }
    
    // public function success(Request $request) {
    //     if(isset($request->session_id)) {
    //         $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));
    //         $response = $stripe->checkout->sessions->retrieve($request->session_id);
    //         // dd($response);
    //         // $payment = new Payment();
    //         // $payment->payment_id = $response->id;
    //         // $payment->product_name = session()->get('product_name');
    //         // $payment->quantity = session()->get('quantity');
    //         // $payment->amount = session()->get('price');
    //         // $payment->currency = $response->currency;
    //         // $payment->customer_name = $response->customer_details->name;
    //         // $payment->customer_email = $response->customer_details->email;
    //         // $payment->payment_status = $response->status;
    //         // $payment->payment_method = "stripe";
    //         // $payment->save();
    //         return("Payment Successfull");

    //     }
    //     else {
    //         return redirect()->route('cancel');
    //     }
    // }

    // public function cancel(Request $request) {
    //     return 'Payment is cancelled';
    // }


    public function createPaymentIntent(Request $request)
    {
        $stripe = new \Stripe\StripeClient(config('stripe.stripe_sk'));

        // Get the total amount from the request
        $finalAmount = $request->input('finalAmount') * 100;

        // Create a PaymentIntent
        $paymentIntent = $stripe->paymentIntents->create([
            'amount' => $finalAmount,
            'currency' => 'aud',
            'payment_method_types' => ['card'],
        ]);

        // Return the client secret to the frontend
        return response()->json(['clientSecret' => $paymentIntent->client_secret]);
    }

    public function storeBooking(Request $request)
    {
        // Create a new booking
        \Log::info($request->all());
        $booking = new BookingModel();
        $booking->classId = $request->classId;
        $booking->studentId = $request->studentId;
        $booking->studentName = $request->studentName;
        $booking->teacherId = $request->teacherId;
        $booking->teacherName = $request->teacherName;
        $booking->startDate = $request->startDate;
        $booking->endDate = $request->endDate;
        $booking->startTime = $request->startTime;
        $booking->endTime = $request->endTime;
        $booking->day = $request->days;
        $booking->noOfStudent = $request->noOfStudent;
        $booking->frequency = $request->frequency;
        $booking->noOfSession = $request->noOfSession;
        $booking->totalAmount = $request->totalAmount;
        $booking->totalDiscount = $request->totalDiscount;
        $booking->finalAmount = $request->finalAmount;
        $booking->transactionId = $request->transactionId;
        $booking->status = 'Paid';
        $booking->save();

        return response()->json(['status' => 'Booking saved successfully']);
    }


    public function paymentSuccess()
    {
        return "Payment successful!";
    }

    public function paymentFailure()
    {
        return "Payment failed!";
    }
}
