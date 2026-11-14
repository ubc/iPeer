import { expect } from '@playwright/test';
import type { ResultSetHeader, RowDataPacket } from 'mysql2/promise';
import type { TestDatabase } from './db';

// Helpers to put test data straight into the database. Adding data through
// the forms is slow, so a test whose subject is not the form inserts the rows
// instead. Each spec builds the rows the way the app saves them, and has one
// test that compares an inserted record with what the form shows.

type Value = string | number | boolean | null | typeof NOW;
export type Row = Record<string, Value>;

// The current time for the created and modified columns. The app writes
// PHP's local time (America/Vancouver, docker/php.ini), not the database
// clock, so inserted rows show the same time as rows the app saves.
// mysql2 writes the SQL that toSqlString returns as it is.
function appTime(): string {
  // The sv-SE locale formats a date as YYYY-MM-DD HH:MM:SS.
  return new Date().toLocaleString('sv-SE', { timeZone: 'America/Vancouver' });
}
export const NOW = { toSqlString: () => `'${appTime()}'` } as const;

// Inserts one row and returns its id.
export async function insertRow(
  { connection }: TestDatabase,
  table: string,
  row: Row
): Promise<string> {
  const [result] = await connection.query<ResultSetHeader>('INSERT INTO ?? SET ?', [table, row]);
  return String(result.insertId);
}

// The id of the one row of a table with this value in a column, such as the
// template with a name. BINARY makes the comparison exact: the tables ignore
// case and trailing spaces otherwise.
export async function findId(
  { connection }: TestDatabase,
  table: string,
  column: string,
  value: string
): Promise<string> {
  const [rows] = await connection.query<RowDataPacket[]>('SELECT id FROM ?? WHERE BINARY ?? = ?', [
    table,
    column,
    value,
  ]);
  expect(rows, `the ${table} row with ${column} ${value}`).toHaveLength(1);
  return String(rows[0].id);
}

export function userId(testDatabase: TestDatabase, username: string): Promise<string> {
  return findId(testDatabase, 'users', 'username', username);
}
