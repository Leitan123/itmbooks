'use client';

import React, { createContext, useCallback, useContext, useMemo, useState } from 'react';
import { useRouter } from 'next/navigation';
import {
  CURRENCIES,
  CurrencyInfo,
  DEFAULT_CURRENCY,
  FormatMoneyOptions,
  formatMoney,
  getCurrencyInfo,
  isValidCurrency,
} from '../lib/currencies';

const API_URL = '/itmbooks.com/api/settings/currency';

interface CurrencyContextValue {
  /** Active currency code, e.g. "USD". */
  currency: string;
  info: CurrencyInfo;
  /** Symbol of the active currency, e.g. "$" or "Rs. ". */
  symbol: string;
  currencies: CurrencyInfo[];
  /** True while a change is being saved. */
  saving: boolean;
  /** Persist a new company currency (optimistic, reverts on failure). */
  setCurrency: (code: string) => Promise<boolean>;
  /** Format an amount in the active currency. */
  format: (amount: number | string | null | undefined, options?: FormatMoneyOptions) => string;
}

const fallbackValue: CurrencyContextValue = {
  currency: DEFAULT_CURRENCY,
  info: getCurrencyInfo(DEFAULT_CURRENCY),
  symbol: getCurrencyInfo(DEFAULT_CURRENCY).symbol,
  currencies: CURRENCIES,
  saving: false,
  setCurrency: async () => false,
  format: (amount, options) => formatMoney(amount, DEFAULT_CURRENCY, options),
};

const CurrencyContext = createContext<CurrencyContextValue>(fallbackValue);

export function CurrencyProvider({
  initialCurrency,
  children,
}: {
  initialCurrency: string;
  children: React.ReactNode;
}) {
  const router = useRouter();
  const [currency, setCurrencyState] = useState<string>(
    isValidCurrency(initialCurrency) ? initialCurrency : DEFAULT_CURRENCY
  );
  const [saving, setSaving] = useState(false);

  const setCurrency = useCallback(
    async (code: string) => {
      if (!isValidCurrency(code) || code === currency) return false;

      const previous = currency;
      setCurrencyState(code); // optimistic
      setSaving(true);
      try {
        const res = await fetch(API_URL, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ currency: code }),
        });
        if (!res.ok) throw new Error(`Request failed with status ${res.status}`);
        // Re-render Server Components that format money on the server.
        router.refresh();
        return true;
      } catch (error) {
        console.error('Failed to save currency', error);
        setCurrencyState(previous);
        alert('Could not change the currency. Please try again.');
        return false;
      } finally {
        setSaving(false);
      }
    },
    [currency, router]
  );

  const value = useMemo<CurrencyContextValue>(() => {
    const info = getCurrencyInfo(currency);
    return {
      currency,
      info,
      symbol: info.symbol,
      currencies: CURRENCIES,
      saving,
      setCurrency,
      format: (amount, options) => formatMoney(amount, currency, options),
    };
  }, [currency, saving, setCurrency]);

  return <CurrencyContext.Provider value={value}>{children}</CurrencyContext.Provider>;
}

/** Access the company currency + formatter from any Client Component. */
export function useCurrency(): CurrencyContextValue {
  return useContext(CurrencyContext);
}
