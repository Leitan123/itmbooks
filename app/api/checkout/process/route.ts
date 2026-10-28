import { NextResponse } from 'next/server';
import { getCybersourceHeaders } from '../../../../lib/cybersourceAuth';

export async function POST(request: Request) {
  try {
    const data = await request.json();
    const { transientToken, amount } = data;

    if (!transientToken) {
      return NextResponse.json({ error: 'Missing transient token' }, { status: 400 });
    }

    // Prepare authorization payload for Cybersource Payments API
    const payload = {
      clientReferenceInformation: {
        code: `SUB-${Date.now()}`
      },
      processingInformation: {
        capture: true, // Auto-capture (auth & capture together)
        commerceIndicator: "internet",
        actionList: ["TOKEN_CREATE"]
      },
      paymentInformation: {
        tokenizedCard: {
          transactionType: "1",
          value: transientToken
        }
      },
      orderInformation: {
        amountDetails: {
          totalAmount: amount || "4500.00",
          currency: "LKR"
        }
      }
    };

    const resourcePath = '/pts/v2/payments';
    const headers = getCybersourceHeaders(payload, resourcePath, 'post');
    const url = `https://apitest.cybersource.com${resourcePath}`;

    const response = await fetch(url, {
      method: 'POST',
      headers: headers,
      body: JSON.stringify(payload)
    });

    const result = await response.json();

    if (!response.ok || result.status !== 'AUTHORIZED' && result.status !== 'PENDING') {
      console.error("Cybersource Payment Error:", result);
      return NextResponse.json({ error: 'Payment failed', details: result }, { status: 400 });
    }

    return NextResponse.json({ success: true, paymentId: result.id, status: result.status });
  } catch (error) {
    console.error("Payment Processing Error:", error);
    return NextResponse.json({ error: 'Internal server error' }, { status: 500 });
  }
}
