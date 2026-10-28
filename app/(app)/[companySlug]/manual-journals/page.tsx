import { getServerSession } from "next-auth";
import { authOptions } from "../../../api/auth/[...nextauth]/route";
import { db } from "../../../../lib/db";
import { redirect } from "next/navigation";
import Link from "next/link";
import "../../../(marketing)/form.css";

export default async function ManualJournalsPage({ params }: { params: { companySlug: string } }) {
  const session = await getServerSession(authOptions);

  if (!session || !session.user) {
    redirect("/login");
  }

  let journals = [];
  try {
    const [rows] = await db.query(
      'SELECT JournalID as id, JournalDate as date, ReferenceNo as reference, Description as notes FROM journal_entries ORDER BY JournalDate DESC'
    ) as any;
    
    if (rows && rows.length > 0) {
      const journalIds = rows.map((j: any) => j.id);
      const [lines] = await db.query(
        `SELECT JournalID as journal_id, DebitAmount as debit, CreditAmount as credit FROM journal_entry_details WHERE JournalID IN (?)`,
        [journalIds]
      ) as any;
      
      journals = rows.map((journal: any) => {
        const jLines = lines.filter((l: any) => l.journal_id === journal.id);
        const total = jLines.reduce((sum: number, l: any) => sum + Number(l.debit), 0);
        return { ...journal, total };
      });
    }
  } catch (error) {
    console.error("Failed to load journals:", error);
  }

  return (
    <>
      <div className="page-header">
        <h1 className="page-title">Manual Journals</h1>
        <div className="page-actions">
          <Link href={`/${params.companySlug}/manual-journals/new`} className="btn btn-primary">+ New Journal</Link>
        </div>
      </div>

      <div className="card">
        <table style={{ width: '100%', borderCollapse: 'collapse' }}>
          <thead>
            <tr style={{ borderBottom: '2px solid #e2e8f0', textAlign: 'left' }}>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569' }}>Date</th>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569' }}>Reference Number</th>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569' }}>Notes</th>
              <th style={{ padding: '12px 16px', fontWeight: '600', color: '#475569', textAlign: 'right' }}>Amount</th>
            </tr>
          </thead>
          <tbody>
            {journals.length === 0 ? (
              <tr>
                <td colSpan={4} style={{ padding: '24px', textAlign: 'center', color: '#64748b' }}>
                  No manual journals found.
                </td>
              </tr>
            ) : (
              journals.map((journal: any) => (
                <tr key={journal.id} style={{ borderBottom: '1px solid #e2e8f0' }}>
                  <td style={{ padding: '12px 16px' }}>{new Date(journal.date).toLocaleDateString()}</td>
                  <td style={{ padding: '12px 16px', fontWeight: '500' }}>{journal.reference || '-'}</td>
                  <td style={{ padding: '12px 16px', color: '#64748b' }}>{journal.notes || '-'}</td>
                  <td style={{ padding: '12px 16px', textAlign: 'right', fontWeight: '600' }}>
                    ${journal.total.toFixed(2)}
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>
    </>
  );
}