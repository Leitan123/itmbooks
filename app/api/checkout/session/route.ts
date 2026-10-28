import { NextResponse } from 'next/server';
import { getCybersourceHeaders } from '../../../../lib/cybersourceAuth';

export async function POST(request: Request) {
  try {
    const origin = process.env.NEXT_PUBLIC_APP_URL || 'http://localhost:3000';
    
    // As per Cybersource Unified Checkout documentation for Session API
    const payload = {
      targetOrigins: [origin],
      clientVersion: "v2.0",
      allowedCardNetworks: ["VISA", "MASTERCARD"],
      allowedPaymentTypes: ["PANENTRY", "SRC", "APPLEPAY", "GOOGLEPAY"]
    };

    const resourcePath = '/up/v1/capture-contexts';
    const headers = getCybersourceHeaders(payload, resourcePath, 'post');

    const url = `https://apitest.cybersource.com${resourcePath}`;

    const response = await fetch(url, {
      method: 'POST',
      headers: headers,
      body: JSON.stringify(payload),
      cache: 'no-store'
    });

    if (!response.ok) {
      const errorText = await response.text();
      console.error("Cybersource Session Error:", errorText);
      return NextResponse.json({ error: 'Failed to generate capture context' }, { status: 500 });
    }

    // The response is usually the Capture Context string itself, typically in JWT format
    // Because Accept header is application/jwt, it returns a plain text JWT.
    const captureContext = await response.text();

    return NextResponse.json({ captureContext });
  } catch (error) {
    console.error("Cybersource API Error:", error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}
