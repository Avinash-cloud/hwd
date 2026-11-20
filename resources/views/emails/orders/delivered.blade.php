@extends('emails.layouts.sacred-mail')

@section('content')
    <div class="greeting">Blessed Delivery Confirmed! 🕉️🙏</div>

    <p style="font-size: 14px; color: #475569;">
        Respected <strong>{{ $order->customer_name }}</strong>, your sacred parcel for order <strong>#{{ $order->order_number }}</strong> has been successfully delivered to your sanctuary.
    </p>

    <div class="blessing-badge">
        "गङ्गे च यमुने चैव गोदावरि सरस्वति । नर्मदे सिन्धु कावेरि जलेऽस्मिन् संनिधिं कुरु ॥"<br>
        May the sacred Gangajal sanctify your home with divine grace, peace, and spiritual wellness.
    </div>

    <!-- Sacred Authenticity Card -->
    <div class="card" style="border: 2px solid #F59E0B; background: #FFFDF7; text-align: center;">
        <div style="font-size: 12px; text-transform: uppercase; color: #B45309; font-weight: 800; letter-spacing: 1px;">
            Provenance & Authenticity Verification
        </div>
        <p style="font-size: 13px; color: #475569; margin: 8px 0 16px;">
            Each bottle is lab-tested and sealed at the auspicious Brahmakund Ghat, Har Ki Pauri.
            You can verify your specific batch collection parameters and water purity report anytime:
        </p>

        <a href="{{ $order->verification_url }}" class="btn" style="background-color: #B45309;">
            Verify Batch Purity & Lab Report ↗
        </a>
    </div>

    <!-- Sacred Usage Instructions -->
    <div class="card">
        <h4 style="margin: 0 0 10px; font-size: 13px; color: #78350F; text-transform: uppercase; font-family: Georgia, serif;">
            Sacred Handling Guidelines
        </h4>
        <ol style="margin: 0; padding-left: 20px; font-size: 12px; color: #475569; line-height: 1.8;">
            <li>Store the sacred Gangajal bottle in your home puja mandir or a clean, elevated sacred spot.</li>
            <li>Before opening for puja or abhishek, wash your hands and chant <em>"Har Har Gange"</em>.</li>
            <li>Suitable for daily Surya Arghya, Shiva Abhishek, home sanctification (Vastu Shuddhi), and auspicious beginnings.</li>
        </ol>
    </div>

    <div style="text-align: center; margin: 25px 0;">
        <a href="{{ route('orders.show', $order->order_number) }}" class="btn-outline">
            View Complete Order Details
        </a>
    </div>
@endsection
