<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class donationController extends Controller
{
    public function donationList()
    {
        return Donation::all();
    }
    public function addDonation(Request $request)
    {
        // return $request;
        $donation = new Donation();
        $donation->name= $request->name;
        $donation->phone= $request->phone;
        $donation->email= $request->email;
        $donation->amount= $request->amount; //dd("hello ". $request->amount);
        $result= $donation->save();
        if ($result)
        {
            $fromDatabase = Donation::find($donation->id);
            $response = [
                "message" => "success",
                "data" => $fromDatabase
            ];
            return response()->json($response);
        }
        else
        {
            return response()->json(["error" => "Failed to add Volunteer"], 500);
        }

    }
    public function processPayment(Request $request)
    {
        try {
            // Validate the request data
            $request->validate([
                'donation_amount' => 'required|numeric|min:1',
            ]);

            // Convert the donation amount to cents
            $amount = $request->donation_amount * 100;

            // Set the Stripe API key
            Stripe::setApiKey(config('services.stripe.secret'));

            // Create a new Stripe Checkout Session
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Donate',
                            'description' => 'Your donation will help us continue improving the lives of internally displaced persons in Ethiopia.',
                            'images' => ['https://ngh1.org/assets/logo-485d8758.png'],
                        ],
                        'unit_amount' => $amount,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => 'http://localhost:3000/success?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => 'http://localhost:3000/cancel',
            ]);

            return response()->json([
                'success' => true,
                'session' => $session,
                'url' => $session->url,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function processSubscription(Request $request)
    {
        // return $request->donation_amount;
        try {

            // Convert the subscription amount to cents
            $amount = $request->donation_amount * 100;
            // return ["##"=>$amount];

            // Define the price ID based on the subscription amount
            $priceID = $this->getPriceID($amount);
            // return ["priceID"=>$priceID];

            if (!$priceID) {
                throw new \Exception('Invalid subscription amount');
            }

            // Set the Stripe API key
            Stripe::setApiKey(config('services.stripe.secret'));

            // Create a new Stripe Checkout Session for subscription
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $priceID,
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => 'http://localhost:3000/success',
                'cancel_url' => 'http://localhost:3000/cancel',
            ]);

            return response()->json([
                'success' => true,
                'session' => $session,
                'url' => $session->url,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    private function getPriceID($amount)
    {


    // Determine the price ID based on the subscription amount
    switch ($amount) {
        case 5000:
            return 'price_1P3faiHGaAONVQkKPYKioXCc';
            // return 'price_1P3fdMHGaAONVQkKFIHtlTAE';
        case 10000:
            return 'price_1P3fdMHGaAONVQkKFIHtlTAE';
        case 20000:
            return 'price_1P3fddHGaAONVQkKLPqbsqxj';
        case 25000:
            return 'price_1P3fdsHGaAONVQkKx3FGbFkc';
        case 50000:
            return 'price_1P3fe7HGaAONVQkKKP8n8f3G';
        // Add other cases as needed
        default:
            try {
                // Set the Stripe API key
                Stripe::setApiKey(config('services.stripe.secret'));

                // Create a new price for the dynamic amount
                $newPrice = \Stripe\Price::create([
                    'unit_amount' => $amount,
                    'currency' => 'usd',
                    'recurring' => [
                        'interval' => 'month',
                    ],
                    // 'product' => 'prod_PlXsWOsJh53OH3',
                    'product' => 'prod_PtSVoGAeLvTUdg',
                ]);

                return $newPrice->id; // Return the ID of the newly created price
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
    }
}

}
