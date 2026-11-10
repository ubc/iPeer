import { readFileSync } from 'fs';
import mysql, { type Connection, type RowDataPacket } from 'mysql2/promise';

// Each test starts from the seed data, so no test depends on what an earlier
// test did, and a test does not have to clean up after itself.
//
// Loading ipeer_samples_data.sql from scratch takes about 160 ms.
// So the seed is loaded once per run into a separate schema,
// and before each test every table of ipeer_test is refilled from
// that copy, which takes about 25 ms.

function mustEnvValue(name: string): string {
  const value = process.env[name];
  if (!value) {
    throw new Error(`${name} is not set. docker-compose.yml sets it in the playwright service.`);
  }
  return value;
}

const APP_DB = () => mustEnvValue('E2E_DB_NAME');
const SEED_DB = () => mustEnvValue('E2E_SEED_DB_NAME');

// The seed creates this table only when it is missing, and puts no rows in it.
// The rows are the sessions of the web server, so a reset keeps them too.
const NOT_RESET = new Set(['cake_sessions']);

function connect(database: string): Promise<Connection> {
  return mysql.createConnection({
    host: mustEnvValue('E2E_DB_HOST'),
    user: mustEnvValue('E2E_DB_USER'),
    password: mustEnvValue('E2E_DB_PASSWORD'),
    database,
    multipleStatements: true,
  });
}

async function loadSeedFile(database: string): Promise<void> {
  const connection = await connect(database);
  try {
    const seedFile = mustEnvValue('E2E_SEED_FILE');
    const seedSql = readFileSync(seedFile, 'utf8');
    // phing removes comment lines before it sends the file, and the file has
    // some, such as "--- START", that MariaDB does not accept as comments.
    const commentLine = /^--.*$/gm;
    const sql = seedSql.replace(commentLine, '');
    await connection.query(sql);
  } finally {
    await connection.end();
  }
}

// Runs once before the tests. Loading the seed into ipeer_test as well makes
// its tables match the seed, also when the schema changed since the last run.
export async function loadSeed(): Promise<void> {
  await loadSeedFile(SEED_DB());
  await loadSeedFile(APP_DB());
}

export type TestDatabase = { readonly connection: Connection; readonly tables: readonly string[] };

export async function openTestDatabase(): Promise<TestDatabase> {
  const connection = await connect(APP_DB());
  const [rows] = await connection.query<RowDataPacket[]>(
    "SELECT table_name AS name FROM information_schema.tables WHERE table_schema = ? AND table_type = 'BASE TABLE'",
    [SEED_DB()]
  );
  const tables = rows.map((row) => String(row.name)).filter((name) => !NOT_RESET.has(name));
  return { connection, tables };
}

export async function resetToSeed({ connection, tables }: TestDatabase): Promise<void> {
  // escapeId(name, true) quotes the whole name, so a dot in it cannot name
  // another schema.
  const seed = mysql.escapeId(SEED_DB(), true);
  // DELETE is much faster than TRUNCATE on tables this small, because TRUNCATE
  // creates each table again.
  const copy = tables.flatMap((table) => {
    const name = mysql.escapeId(table, true);
    return [`DELETE FROM ${name}`, `INSERT INTO ${name} SELECT * FROM ${seed}.${name}`];
  });
  await connection.query(
    ['SET foreign_key_checks = 0', ...copy, 'SET foreign_key_checks = 1'].join(';\n')
  );
  await resetAutoIncrements(connection);
}

// DELETE does not reset the AUTO_INCREMENT counters. Put back the ones that a
// test moved, so that a test gets the same ids whether it runs alone or after
// other tests. Changing a counter is slow, so change only those.
async function resetAutoIncrements(connection: Connection): Promise<void> {
  const [moved] = await connection.query<RowDataPacket[]>(
    `SELECT test.table_name AS name, seed.auto_increment AS next_id
         FROM information_schema.tables test
                  JOIN information_schema.tables seed
                       ON seed.table_schema = ? AND seed.table_name = test.table_name
         WHERE test.table_schema = DATABASE()
           AND test.auto_increment <> seed.auto_increment`,
    [SEED_DB()]
  );
  if (moved.length > 0) {
    await connection.query(
      moved
        .map(
          (row) =>
            // this effectively copies the "next" value from the `seed` DB into the main test DB
            `ALTER TABLE ${mysql.escapeId(String(row.name), true)} AUTO_INCREMENT = ${Number(row.next_id)}`
        )
        .join(';\n')
    );
  }
}
