import { tmpdir } from 'os';
import path from 'path';
import { loadSeed } from './db';

export default async function globalSetup(): Promise<void> {
  // Workers inherit the environment of this process. support/test.ts writes
  // this file when a reset fails, and then skips every later test of the run.
  process.env.E2E_RESET_FAILED_MARKER = path.join(tmpdir(), `e2e-reset-failed-${Date.now()}`);
  await loadSeed();
}
