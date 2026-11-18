import { expect } from '@playwright/test';

// The text of each page of a PDF file. TCPDF compresses the page contents, so
// a plain search of the file finds nothing.
export async function pdfPages(content: Buffer): Promise<string[]> {
  // unpdf is an ES module, and the specs are CommonJS, so it is loaded with a
  // dynamic import.
  const { extractText } = await import('unpdf');
  const { text } = await extractText(new Uint8Array(content), { mergePages: false });
  return text;
}

// The layout of a PDF decides where its text breaks into lines and how many
// spaces it has. So the checks ignore all whitespace, and check only the
// order of the text: each check is a list of parts, and each part must come
// soon after the one before it.
const squash = (text: string) => text.replace(/\s+/g, '');

// Reads a text from the start to the end. expect() checks that the parts
// come in order: the first part anywhere after the previous check, and each
// next part within maxGap characters (not counting whitespace) after the one
// before it. A page footer or a table header between two parts fits in the
// gap, a missing or moved line does not. The checks are soft, so a run
// reports every part that is missing.
export function readInOrder(text: string) {
  const haystack = squash(text);
  let from = 0;
  return {
    expect(parts: readonly string[], maxGap = 60): void {
      let at = from;
      for (const [index, part] of parts.entries()) {
        const needle = squash(part);
        const found = haystack.indexOf(needle, at);
        const near = found >= 0 && (index === 0 || found - at <= maxGap);
        const where = index === 0 ? 'after the previous check' : `after '${parts[index - 1]}'`;
        expect.soft(near, `'${part}' ${where}, in: ${haystack.slice(at, at + 150)}`).toBe(true);
        if (!near) {
          return;
        }
        at = found + needle.length;
      }
      from = at;
    },
  };
}
