import { db } from './db';
import { DEFAULT_CURRENCY, isValidCurrency } from './currencies';

/**
 * Adds the `currency` column to `tenants` if it does not exist yet.
 * Safe to call repeatedly / concurrently (duplicate-column errors are ignored).
 */
async function ensureCurrencyColumn(): Promise<void> {
  try {
    await db.query(
      `ALTER TABLE tenants ADD COLUMN currency VARCHAR(10) NOT NULL DEFAULT '${DEFAULT_CURRENCY}'`
    );
  } catch (e: any) {
    // 1060 = ER_DUP_FIELDNAME -> column already exists (e.g. created by a concurrent request)
    if (e?.errno !== 1060) throw e;
  }
}

function isMissingColumnError(e: any): boolean {
  // 1054 = ER_BAD_FIELD_ERROR
  return e?.errno === 1054 || e?.code === 'ER_BAD_FIELD_ERROR';
}

/** Returns the company's display currency code (falls back to USD). */
export async function getTenantCurrency(tenantId: number | string | null | undefined): Promise<string> {
  if (tenantId === null || tenantId === undefined || tenantId === '') return DEFAULT_CURRENCY;

  const read = async () => {
    const [rows] = (await db.query('SELECT currency FROM tenants WHERE id = ?', [tenantId])) as any;
    const code = rows?.[0]?.currency;
    return isValidCurrency(code) ? code : DEFAULT_CURRENCY;
  };

  try {
    return await read();
  } catch (e: any) {
    if (isMissingColumnError(e)) {
      try {
        await ensureCurrencyColumn();
        return await read();
      } catch (inner) {
        console.error('Failed to initialise tenant currency column:', inner);
      }
    } else {
      console.error('Failed to read tenant currency:', e);
    }
    return DEFAULT_CURRENCY;
  }
}

/** Persists the company's display currency. Throws on an invalid code. */
export async function setTenantCurrency(tenantId: number | string, code: string): Promise<void> {
  if (!isValidCurrency(code)) {
    throw new Error(`Unsupported currency: ${code}`);
  }

  const write = () => db.query('UPDATE tenants SET currency = ? WHERE id = ?', [code, tenantId]);

  try {
    await write();
  } catch (e: any) {
    if (!isMissingColumnError(e)) throw e;
    await ensureCurrencyColumn();
    await write();
  }
}
