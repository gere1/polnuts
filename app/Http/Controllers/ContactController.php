<?php

namespace App\Http\Controllers;

use App\Models\ContactSubmission;
use App\Models\Setting;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'captcha_answer' => ['required', 'numeric', function ($attribute, $value, $fail) use ($request) {
                if ((int) $value !== $this->decryptedInt($request->input('captcha_expected'))) {
                    $fail('არასწორი პასუხი, სცადეთ თავიდან');
                }
            }],
        ]);

        // Honeypot: real visitors never see or fill this field.
        if ($request->filled('website')) {
            return back()->with('status', 'Your message has been sent. We will contact you soon.');
        }

        // Bots typically submit far faster than a human can fill the form.
        $renderedAt = $this->decryptedInt($request->input('form_rendered_at'));

        if ($renderedAt === null || (time() - $renderedAt) < 3) {
            return back()->with('status', 'Your message has been sent. We will contact you soon.');
        }

        ContactSubmission::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'message' => $data['message'],
        ]);

        $adminEmail = Setting::current()->email;

        if ($adminEmail) {
            try {
                Mail::raw(
                    "სახელი: {$data['name']}\nელ. ფოსტა: {$data['email']}\n\n{$data['message']}",
                    function ($mail) use ($adminEmail, $data) {
                        $mail->to($adminEmail)
                            ->subject('ახალი შეტყობინება საიტიდან')
                            ->replyTo($data['email'], $data['name']);
                    }
                );
            } catch (\Throwable $e) {
                Log::warning('Contact form mail failed: ' . $e->getMessage());
            }
        }

        return back()->with('status', 'Your message has been sent. We will contact you soon.');
    }

    /**
     * Decrypt an encrypted integer produced by the form (timestamp or expected
     * captcha sum). Missing or tampered-with values decrypt to null rather than
     * throwing, so callers can treat them as a failed bot check.
     */
    private function decryptedInt(?string $value): ?int
    {
        if ($value === null) {
            return null;
        }

        try {
            return (int) decrypt($value);
        } catch (DecryptException) {
            return null;
        }
    }
}
