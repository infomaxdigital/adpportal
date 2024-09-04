<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BookingModel;

class PaymentController extends Controller
{
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
        $booking->classType = $request->classType;
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
        $booking->partnerName = $request->partnername;
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
