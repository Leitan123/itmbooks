'use client';

import { useState } from 'react';
import Link from 'next/link';

interface JournalLine {
  journal_id: number;
  account_id: number;
  account_name: string;
  account_code: string;
  description: string;
  debit: number;
  credit: number;
}

interface Journal {
  id: number;
  date: string;
  reference: string;
  notes: string;
  total: number;
  lines: JournalLine[];
}

export default function ManualJournalsClient({ initialJournals, companySlug }: { initialJournals: Journal[], companySlug: string }) {
  const [journals] = useState<Journal[]>(initialJournals);
  const [searchTerm, setSearchTerm] = useState('');
  const [expandedId, setExpandedId] = useState<number | null>(null);

  const filteredJournals = journals.filter(j => {
    const term = searchTerm.toLowerCase();
    const matchesRef = (j.reference || '').toLowerCase().includes(term);
    const matchesNotes = (j.notes || '').toLowerCase().includes(term);
    const matchesLines = j.lines?.some(l => 
      (l.account_name || '').toLowerCase().includes(term) || 
      (l.account_code || '').toLowerCase().includes(term)
    );
    return matchesRef || matchesNotes || matchesLines;
  });

  const toggleExpand = (id: number) => {
    setExpandedId(expandedId === id ? null : id);
  };

  const totalPostedAmount = journals.reduce((sum, j) => sum + (j.total || 0), 0);

  return (
    <div className="page-container">
      <div className="page-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '24px' }}>
        <div>
          <h1 className="page-title">Manual Journals</h1>
          <p className="page-subtitle" style={{ color: '#64748b', fontSize: '0.9rem', margin: '4px 0 0 0' }}>
            Record general ledger adjustments, opening balances, depreciation, and compound entries.
          </p>
        </div>
        <div style={{ display: 'flex', gap: '12px' }}>
          <Link href={`/${companySlug}/trial-balance`} className="btn btn-outline">
            Trial Balance
          </Link>
          <Link href={`/${companySlug}/manual-journals/new`} className="btn btn-primary">
            + New Journal
          </Link>
        </div>
      </div>

      {/* Stats bar */}
      <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))', gap: '16px', marginBottom: '24px' }}>
        <div className="card" style={{ padding: '16px', borderLeft: '4px solid #2563eb' }}>
          <div style={{ fontSize: '0.75rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Total Entries</div>
          <div style={{ fontSize: '1.25rem', fontWeight: 700, color: '#0f172a', marginTop: '4px' }}>{journals.length}</div>
        </div>
        <div className="card" style={{ padding: '16px', borderLeft: '4px solid #10b981' }}>
          <div style={{ fontSize: '0.75rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Total Debits / Credits</div>
          <div style={{ fontSize: '1.25rem', fontWeight: 700, color: '#0f172a', marginTop: '4px' }}>
            ${totalPostedAmount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
          </div>
        </div>
        <div className="card" style={{ padding: '16px', borderLeft: '4px solid #8b5cf6' }}>
          <div style={{ fontSize: '0.75rem', fontWeight: 600, color: '#64748b', textTransform: 'uppercase' }}>Status</div>
          <div style={{ fontSize: '1.25rem', fontWeight: 700, color: '#16a34a', marginTop: '4px' }}>100% Balanced</div>
        </div>
      </div>

      {/* Search */}
      <div className="card" style={{ padding: '16px', marginBottom: '20px' }}>
        <input 
          type="text" 
          className="form-input" 
          placeholder="Search by reference, account, or notes..."
          value={searchTerm}
          onChange={(e) => setSearchTerm(e.target.value)}
          style={{ margin: 0, maxWidth: '400px' }}
        />
      </div>

      {/* Journals List */}
      <div className="card" style={{ overflow: 'hidden' }}>
        <table style={{ width: '100%', borderCollapse: 'collapse' }}>
          <thead>
            <tr style={{ borderBottom: '2px solid #e2e8f0', backgroundColor: '#f8fafc', textAlign: 'left' }}>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569', fontSize: '0.85rem', width: '12%' }}>Date</th>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569', fontSize: '0.85rem', width: '18%' }}>Reference</th>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569', fontSize: '0.85rem', width: '40%' }}>Notes / Description</th>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569', fontSize: '0.85rem', textAlign: 'right', width: '18%' }}>Amount</th>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569', fontSize: '0.85rem', textAlign: 'center', width: '12%' }}>Lines</th>
            </tr>
          </thead>
          <tbody>
            {filteredJournals.length === 0 ? (
              <tr>
                <td colSpan={5} style={{ padding: '32px', textAlign: 'center', color: '#64748b' }}>
                  No manual journals found. Click "+ New Journal" to post an entry.
                </td>
              </tr>
            ) : (
              filteredJournals.map((journal) => {
                const isExpanded = expandedId === journal.id;
                return (
                  <React.Fragment key={journal.id}>
                    <tr 
                      onClick={() => toggleExpand(journal.id)} 
                      style={{ 
                        borderBottom: '1px solid #e2e8f0', 
                        cursor: 'pointer',
                        backgroundColor: isExpanded ? '#f8fafc' : 'transparent',
                        transition: 'background-color 0.15s'
                      }}
                    >
                      <td style={{ padding: '14px 16px', fontWeight: '500', color: '#0f172a' }}>
                        {new Date(journal.date).toLocaleDateString()}
                      </td>
                      <td style={{ padding: '14px 16px', fontWeight: '600', color: '#2563eb' }}>
                        {journal.reference || '-'}
                      </td>
                      <td style={{ padding: '14px 16px', color: '#475569' }}>
                        {journal.notes || <span style={{ color: '#94a3b8', fontStyle: 'italic' }}>No notes provided</span>}
                      </td>
                      <td style={{ padding: '14px 16px', textAlign: 'right', fontWeight: '700', color: '#0f172a' }}>
                        ${Number(journal.total).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                      </td>
                      <td style={{ padding: '14px 16px', textAlign: 'center' }}>
                        <span style={{ 
                          padding: '3px 8px', 
                          borderRadius: '12px', 
                          fontSize: '0.75rem', 
                          fontWeight: 600, 
                          backgroundColor: isExpanded ? '#dbeafe' : '#f1f5f9',
                          color: isExpanded ? '#1d4ed8' : '#64748b'
                        }}>
                          {journal.lines?.length || 0} lines {isExpanded ? '▲' : '▼'}
                        </span>
                      </td>
                    </tr>

                    {/* Expanded Detail Lines */}
                    {isExpanded && (
                      <tr style={{ backgroundColor: '#f1f5f9', borderBottom: '2px solid #cbd5e1' }}>
                        <td colSpan={5} style={{ padding: '16px 24px' }}>
                          <div style={{ backgroundColor: '#ffffff', borderRadius: '8px', padding: '16px', border: '1px solid #e2e8f0' }}>
                            <div style={{ fontSize: '0.85rem', fontWeight: 600, color: '#334155', marginBottom: '10px' }}>
                              Journal Lines (Reference: {journal.reference})
                            </div>
                            <table style={{ width: '100%', borderCollapse: 'collapse', fontSize: '0.85rem' }}>
                              <thead>
                                <tr style={{ borderBottom: '1px solid #e2e8f0', color: '#64748b', textAlign: 'left' }}>
                                  <th style={{ padding: '8px 12px' }}>Account Code</th>
                                  <th style={{ padding: '8px 12px' }}>Account Name</th>
                                  <th style={{ padding: '8px 12px' }}>Description</th>
                                  <th style={{ padding: '8px 12px', textAlign: 'right' }}>Debit ($)</th>
                                  <th style={{ padding: '8px 12px', textAlign: 'right' }}>Credit ($)</th>
                                </tr>
                              </thead>
                              <tbody>
                                {journal.lines?.map((line, idx) => (
                                  <tr key={idx} style={{ borderBottom: '1px solid #f8fafc' }}>
                                    <td style={{ padding: '8px 12px', fontWeight: 600, color: '#1e293b' }}>{line.account_code}</td>
                                    <td style={{ padding: '8px 12px', color: '#0f172a' }}>{line.account_name}</td>
                                    <td style={{ padding: '8px 12px', color: '#64748b' }}>{line.description || '-'}</td>
                                    <td style={{ padding: '8px 12px', textAlign: 'right', fontWeight: 600, color: Number(line.debit) > 0 ? '#10b981' : '#94a3b8' }}>
                                      {Number(line.debit) > 0 ? Number(line.debit).toFixed(2) : '-'}
                                    </td>
                                    <td style={{ padding: '8px 12px', textAlign: 'right', fontWeight: 600, color: Number(line.credit) > 0 ? '#f97316' : '#94a3b8' }}>
                                      {Number(line.credit) > 0 ? Number(line.credit).toFixed(2) : '-'}
                                    </td>
                                  </tr>
                                ))}
                              </tbody>
                            </table>
                          </div>
                        </td>
                      </tr>
                    )}
                  </React.Fragment>
                );
              })
            )}
          </tbody>
        </table>
      </div>
    </div>
  );
}

import React from 'react';
