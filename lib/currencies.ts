/**
 * Shared currency definitions + formatter.
 *
 * Pure module (no React, no 'use client') so it can be imported from both
 * Server Components and Client Components.
 *
 * NOTE: Changing the company currency only changes how amounts are *displayed*
 * (symbol + number formatting). Stored amounts are never converted.
 */

export interface CurrencyInfo {
  code: string;
  name: string;
  /** Symbol rendered before the number. May include a trailing space (e.g. "Rs. "). */
  symbol: string;
  flag: string;
  /** Number of fraction digits normally shown for this currency. */
  decimals: number;
}

export const CURRENCIES: CurrencyInfo[] = [
  { code: 'USD', name: 'US Dollar', symbol: '$', flag: '🇺🇸', decimals: 2 },
  { code: 'EUR', name: 'Euro', symbol: '€', flag: '🇪🇺', decimals: 2 },
  { code: 'GBP', name: 'British Pound', symbol: '£', flag: '🇬🇧', decimals: 2 },
  { code: 'LKR', name: 'Sri Lankan Rupee', symbol: 'Rs. ', flag: '🇱🇰', decimals: 2 },
  { code: 'INR', name: 'Indian Rupee', symbol: '₹', flag: '🇮🇳', decimals: 2 },
  { code: 'AUD', name: 'Australian Dollar', symbol: 'A$', flag: '🇦🇺', decimals: 2 },
  { code: 'CAD', name: 'Canadian Dollar', symbol: 'C$', flag: '🇨🇦', decimals: 2 },
  { code: 'SGD', name: 'Singapore Dollar', symbol: 'S$', flag: '🇸🇬', decimals: 2 },
  { code: 'AED', name: 'UAE Dirham', symbol: 'AED ', flag: '🇦🇪', decimals: 2 },
  { code: 'SAR', name: 'Saudi Riyal', symbol: 'SAR ', flag: '🇸🇦', decimals: 2 },
  { code: 'MYR', name: 'Malaysian Ringgit', symbol: 'RM ', flag: '🇲🇾', decimals: 2 },
  { code: 'JPY', name: 'Japanese Yen', symbol: '¥', flag: '🇯🇵', decimals: 0 },
  { code: 'CNY', name: 'Chinese Yuan', symbol: 'CN¥', flag: '🇨🇳', decimals: 2 },
  { code: 'ZAR', name: 'South African Rand', symbol: 'R ', flag: '🇿🇦', decimals: 2 },
];

export const DEFAULT_CURRENCY = 'USD';

export function isValidCurrency(code: unknown): code is string {
  return typeof code === 'string' && CURRENCIES.some((c) => c.code === code);
}

export function getCurrencyInfo(code?: string | null): CurrencyInfo {
  return (
    CURRENCIES.find((c) => c.code === code) ||
    CURRENCIES.find((c) => c.code === DEFAULT_CURRENCY)!
  );
}

export interface FormatMoneyOptions {
  /** Override the number of fraction digits (defaults to the currency's own). */
  decimals?: number;
}

/**
 * Format an amount for display in the given currency code.
 * Negative amounts render as "-$1,234.00".
 */
export function formatMoney(
  amount: number | string | null | undefined,
  code?: string | null,
  options: FormatMoneyOptions = {}
): string {
  const info = getCurrencyInfo(code);
  const n = Number(amount);
  const value = Number.isFinite(n) ? n : 0;
  const digits = options.decimals ?? info.decimals;

  const body = Math.abs(value).toLocaleString('en-US', {
    minimumFractionDigits: digits,
    maximumFractionDigits: digits,
  });

  // Avoid rendering "-$0.00" for values that round to zero.
  const isNegative = value < 0 && Number(body.replace(/,/g, '')) !== 0;
  return `${isNegative ? '-' : ''}${info.symbol}${body}`;
}
