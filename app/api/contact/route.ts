import { NextResponse } from 'next/server';

export async function POST(request: Request) {
  try {
    const body = await request.json();
    
    // In a production environment, this would save to the database 
    // and send email notifications via an SMTP service (e.g., SendGrid).
    console.log('Received contact submission:', body);

    return NextResponse.json(
      { message: 'Contact form submitted successfully.', success: true },
      { status: 200 }
    );
  } catch (error) {
    return NextResponse.json(
      { message: 'An error occurred while processing the request.', success: false },
      { status: 500 }
    );
  }
}
