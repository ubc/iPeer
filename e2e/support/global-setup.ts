import { loadSeed } from './db';

export default async function globalSetup(): Promise<void> {
  await loadSeed();
}
