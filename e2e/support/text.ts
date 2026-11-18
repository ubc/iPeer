import { expect, test, type Locator } from '@playwright/test';

// Checks of text whose layout can change: the line breaks and spaces of a
// page, a table cell or a PDF depend on the markup and the styles. So these
// checks ignore all whitespace, and check only the order of the text: each
// check is a list of parts, and each part must come soon after the one
// before it. A label or a page footer between two parts fits in the gap; a
// missing, changed or moved part does not.

// The text without any whitespace.
export const squash = (text: string) => text.replace(/\s+/g, '');

// Finds the parts in order, from a start position. Returns where the last
// part ends, or a description of the first part that is missing.
function findInOrder(
  haystack: string,
  parts: readonly string[],
  start: number,
  maxGap: number
): { end: number } | { missing: string } {
  let at = start;
  for (const [index, part] of parts.entries()) {
    const needle = squash(part);
    const found = haystack.indexOf(needle, at);
    if (found < 0 || (index > 0 && found - at > maxGap)) {
      const where = index === 0 ? 'after the previous check' : `soon after '${parts[index - 1]}'`;
      return { missing: `'${part}' ${where}, in: ${haystack.slice(at, at + 150)}` };
    }
    at = found + needle.length;
  }
  return { end: at };
}

// Reads a text from the start to the end: each expect() checks its parts in
// order, after the parts of the check before it. The first part can be
// anywhere after the previous check, each next part within maxGap
// characters (not counting whitespace) after the one before it. The checks
// are soft, so a run reports every part that is missing.
export function readInOrder(text: string) {
  const haystack = squash(text);
  let from = 0;
  return {
    expect(parts: readonly string[], maxGap = 60): void {
      const result = findInOrder(haystack, parts, from, maxGap);
      if ('missing' in result) {
        expect.soft(result.missing, 'text in order').toBe('');
      } else {
        from = result.end;
      }
    },
  };
}

// Waits until the text of an element has the parts in order, as above.
export async function expectTextInOrder(
  locator: Locator,
  parts: readonly string[],
  maxGap = 60
): Promise<void> {
  await test.step(
    `Expect the text in order: ${parts.join(' … ')}`,
    async () => {
      await expect
        .poll(async () => {
          const result = findInOrder(squash(await locator.innerText()), parts, 0, maxGap);
          return 'missing' in result ? result.missing : '';
        })
        .toBe('');
    },
    { box: true }
  );
}
