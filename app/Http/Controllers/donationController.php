<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use Stripe\Stripe;
use Stripe\Checkout\Session;


class donationController extends Controller
{




public function sendTestEmail($name, $amount, $email)
{
    try {
        $from = 'samuel.abewa@gmail.com';
        $ccEmails = ['nahomdebele002@gmail.com'];
        $subject = "Subject: Receipt";
        $body = "Greetings,\nAttached is your receipt for your donation. Thank you so much for your kindness and generosity.\nGenet Hailemichael";
        $date = Carbon::now()->format('F j, Y'); // Format the date
        $emailBody = "From: $from\nDate: $date\nTo: $email\nCc: " . implode(', ', $ccEmails) . "\n\n" . $body;

        // Load the Word template
        $template = new TemplateProcessor(public_path('PaySlipTemplate.docx'));
        // Set dynamic values in the template
        $template->setValue('name', $name);
        $template->setValue('amount', $amount);
        $template->setValue('email', $email);
        $template->setValue('date', $date);

        // Save the modified template as a new Word document
        $wordFilePath = public_path('invoice.docx');
        $template->saveAs($wordFilePath);

        // Convert Word to HTML
        $phpWord = IOFactory::load($wordFilePath);
        $htmlFilePath = public_path('invoice.html');
        $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
        $htmlWriter->save($htmlFilePath);

        // Initialize Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        // Load HTML content into Dompdf
        $dompdf->loadHtml(file_get_contents($htmlFilePath));
        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');
        // Render the HTML as PDF
        $dompdf->render();

        // Save the PDF to file
        $pdfFilePath = public_path('invoice.pdf');
        file_put_contents($pdfFilePath, $dompdf->output());

        // Send email with the PDF attachment
        Mail::raw($emailBody, function ($message) use ($pdfFilePath, $email, $subject) {
            $message->to($email)
                    ->subject($subject)
                    ->attach($pdfFilePath);
        });

        // Optionally, delete temporary files after sending the email
        @unlink($wordFilePath); // Deletes the Word file
        @unlink($htmlFilePath); // Deletes the HTML file
        // @unlink($pdfFilePath); // Uncomment to delete the PDF file if no longer needed

    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
}

public function addDonation(Request $request)
{
    $donation = new Donation();
    $donation->name = $request->name;
    $donation->phone = $request->phone;
    $donation->email = $request->email;
    $donation->amount = $request->amount;

    // Save the donation
    $result = $donation->save();
    if ($result) {
        // Send the test email
        $this->sendTestEmail($donation->name, $donation->amount, $donation->email);

        // Retrieve the saved donation data
        $fromDatabase = Donation::find($donation->id);
        $response = [
            "message" => "success",
            "data" => $fromDatabase
        ];
        return response()->json($response);
    } else {
        return response()->json(["error" => "Failed to add Donation"], 500);
    }
}


     public function donationList()
    {
         $response = [
            "success" => true,
            "allBalance" => Donation::all()
            // select('id', 'name', 'email', 'phone', 'amount')
            // ->get()
            // ->map(function ($donation) {
            //     $donation->amount = (int)$donation->amount; // Convert amount to integer
            //     return $donation;
            // })
                // "allBalance" => Donation::all()
            ];
            return response()->json($response);
        // return Donation::all();
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
                'success_url' => 'https://ngh1.org/success',
                'cancel_url' => 'https://ngh1.org/cancel',
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
                'success_url' => 'https://ngh1.org/success',
                'cancel_url' => 'https://ngh1.org/cancel',
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
        switch ($amount) {
            case 5000:
                return 'price_1P3faiHGaAONVQkKPYKioXCc';
            case 10000:
                return 'price_1P3fdMHGaAONVQkKFIHtlTAE';
            case 20000:
                return 'price_1P3fddHGaAONVQkKLPqbsqxj';
            case 25000:
                return 'price_1P3fdsHGaAONVQkKx3FGbFkc';
            case 50000:
                return 'price_1P3fe7HGaAONVQkKKP8n8f3G';
            default:
                try {
                    Stripe::setApiKey(config('services.stripe.secret'));

                    $newPrice = \Stripe\Price::create([
                        'unit_amount' => $amount,
                        'currency' => 'usd',
                        'recurring' => [
                            'interval' => 'month',
                        ],
                        'product' => 'prod_PtSVoGAeLvTUdg',
                    ]);

                    return $newPrice->id;
                } catch (\Exception $e) {
                    throw new \Exception($e->getMessage());
                }
        }
    }
}
