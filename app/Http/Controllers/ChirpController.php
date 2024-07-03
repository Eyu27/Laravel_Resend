<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Resend\Laravel\Facades\Resend;
use Illuminate\Http\RedirectResponse;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('chirps.index', [
            'chirps' => Chirp::with('user')->latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255'
        ]);

        $request->user()->chirps()->create($validated);

        // HTML content for the email
        // Send an email
        $res = Resend::emails()->send([
            'from' => 'Eyuel <onboarding@resend.dev>',
            'to' => ["eyubeten@gmail.com"],
            'subject' => 'hello world',
            'html' => '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Email Template</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background-color: #f4f4f4;
                }
                .email-container {
                    max-width: 600px;
                    margin: auto;
                    background-color: #ffffff;
                    padding: 20px;
                    border: 1px solid #dddddd;
                }
                .header {
                    background-color: #007BFF;
                    color: #ffffff;
                    padding: 10px;
                    text-align: center;
                }
                .header h1 {
                    margin: 0;
                }
                .content {
                    padding: 20px;
                    text-align: left;
                }
                .content p {
                    line-height: 1.6;
                }
                .footer {
                    background-color: #f4f4f4;
                    color: #555555;
                    padding: 10px;
                    text-align: center;
                    border-top: 1px solid #dddddd;
                }
                .button {
                    display: inline-block;
                    padding: 10px 20px;
                    margin: 20px 0;
                    background-color: #007BFF;
                    color: #ffffff;
                    text-decoration: none;
                    border-radius: 5px;
                }
            </style>
        </head>
        <body>
            <div class="email-container">
                <div class="header">
                    <h1>Welcome to Our Service</h1>
                </div>
                <div class="content">
                    <h2>Hello, Eyuel!</h2>
                    <p>We are excited to have you on board. This is a test email from Resend, and we hope you find our service useful.</p>
                    <p>If you have any questions or need assistance, feel free to contact us anytime.</p>
                    <a href="https://betenethiopia.com" class="button">Get Started</a>
                </div>
                <div class="footer">
                    <p>&copy; 2024 Our Service. All rights reserved.</p>
                    <p>1234 Street Name, City, State, 12345</p>
                </div>
            </div>
        </body>
        </html>',
        ]);



        // dd($res);



        return redirect(route('chirps.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp): View
    {
        $this->authorize('update', $chirp);

        return view('chirps.edit', [
            'chirp' => $chirp
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp)
    {
        $this->authorize('update', $chirp);

        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $chirp->update($validated);

        return redirect(route('chirps.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp): RedirectResponse
    {
        $this->authorize('delete', $chirp);

        $chirp->delete();

        return redirect(route('chirps.index'));
    }
}
