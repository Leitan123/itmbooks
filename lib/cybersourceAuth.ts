import crypto from 'crypto';

export function getCybersourceHeaders(
  payload: any,
  resourcePath: string,
  method: 'get' | 'post' = 'post'
) {
  const merchantId = process.env.CYBERSOURCE_MERCHANT_ID || 'dummy_merchant_id';
  const apiKeyId = process.env.CYBERSOURCE_API_KEY_ID || 'dummy_api_key_id';
  const apiSecretKey = process.env.CYBERSOURCE_API_SECRET_KEY || 'dummy_api_secret_key';
  
  const host = 'apitest.cybersource.com';
  const date = new Date().toUTCString();

  // 1. Calculate Digest (SHA-256 hash of payload, base64 encoded)
  let digest = '';
  let payloadString = '';
  if (method === 'post' && payload) {
    payloadString = typeof payload === 'string' ? payload : JSON.stringify(payload);
    const hash = crypto.createHash('sha256').update(payloadString, 'utf8').digest();
    digest = `SHA-256=${hash.toString('base64')}`;
  }

  // 2. Build Signature String
  let signatureString = `host: ${host}\ndate: ${date}\n(request-target): ${method} ${resourcePath}\n`;
  if (method === 'post') {
    signatureString += `digest: ${digest}\n`;
  }
  signatureString += `v-c-merchant-id: ${merchantId}`;

  // 3. HMAC-SHA256 the Signature String with the Secret Key
  const decodedSecret = Buffer.from(apiSecretKey, 'base64');
  const hmac = crypto.createHmac('sha256', decodedSecret);
  hmac.update(signatureString, 'utf8');
  const signatureHash = hmac.digest('base64');

  // 4. Construct the final Signature Header
  let headersToSign = 'host date (request-target)';
  if (method === 'post') {
    headersToSign += ' digest';
  }
  headersToSign += ' v-c-merchant-id';

  const signatureHeader = `keyid="${apiKeyId}", algorithm="HmacSHA256", headers="${headersToSign}", signature="${signatureHash}"`;

  // 5. Return the required headers
  const headers: Record<string, string> = {
    'v-c-merchant-id': merchantId,
    'Date': date,
    'Host': host,
    'Signature': signatureHeader,
    'Accept': 'application/jwt',
  };

  if (method === 'post') {
    headers['Digest'] = digest;
    headers['Content-Type'] = 'application/json';
  }

  return headers;
}
