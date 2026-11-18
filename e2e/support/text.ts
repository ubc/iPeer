import { expect, test, type Locator } from '@playwright/test';

// Checks of text whose layout can change: the line breaks and spaces of a
// page, a table cell or a PDF depend on the markup and the styles. So these
// checks ignore all whitespace, and check only the order of the text: each
// check is a list of parts, and each part must come soon after the one
// before it. A label or a page footer between two parts fits in the gap; a
// missing, changed or moved part does not.

// The text without any whitespace.
export const squash = (text: string) => text.replace(/\s+/g, '');

// The text with each run of whitespace as one space, for compares that keep
// the words apart.
export const collapse = (text: string) => text.replace(/\s+/g, ' ').trim();

// Finds the parts in order, from a start position. Each match of the first
// part is tried in turn, so an earlier, unrelated match does not fail the
// check. Returns where the last part ends, or a description of the part that
// is missing after the best try.
function findInOrder(
  haystack: string,
  parts: readonly string[],
  start: number,
  maxGap: number,
  firstWhere: string
): { end: number } | { missing: string } {
  const first = squash(parts[0]);
  let missing = `'${parts[0]}' ${firstWhere}, in: ${haystack.slice(start, start + 150)}`;
  let deepest = -1;
  for (
    let found = haystack.indexOf(first, start);
    found >= 0;
    found = haystack.indexOf(first, found + 1)
  ) {
    let at = found + first.length;
    let index = 1;
    for (; index < parts.length; index++) {
      const next = haystack.indexOf(squash(parts[index]), at);
      if (next < 0 || next - at > maxGap) {
        break;
      }
      at = next + squash(parts[index]).length;
    }
    if (index === parts.length) {
      return { end: at };
    }
    if (index > deepest) {
      deepest = index;
      missing = `'${parts[index]}' within ${maxGap} characters after '${parts[index - 1]}', in: ${haystack.slice(at, at + 150)}`;
    }
  }
  return { missing };
}

// Reads a text from the start to the end: each expect() checks its parts in
// order, after the parts of the check before it. The first part can be
// anywhere after the previous check. Each next part must start within
// maxGap characters (not counting whitespace) after the end of the part
// before it. The checks are soft, so a run reports every part that is
// missing.
export function readInOrder(text: string) {
  const haystack = squash(text);
  let from = 0;
  return {
    expect(parts: readonly string[], maxGap = 60): void {
      const where = from === 0 ? 'in the text' : 'after the previous check';
      const result = findInOrder(haystack, parts, from, maxGap, where);
      if ('missing' in result) {
        expect.soft(result.missing, 'text in order').toBe('');
      } else {
        from = result.end;
      }
    },
  };
}

// Waits until the text of an element has the parts in order, as above. Use
// it when the element has other text, such as labels, between the parts.
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
          const text = squash(await locator.innerText());
          const result = findInOrder(text, parts, 0, maxGap, 'in the text');
          return 'missing' in result ? result.missing : '';
        })
        .toBe('');
    },
    { box: true }
  );
}

// Waits until the whole text of an element, or of each element that the
// locator finds, is the expected text. Line breaks and runs of spaces do not
// count, so a change of markup does not fail the check, but any other text
// does. Use it when the whole text is known.
export async function expectTextIgnoringSpace(
  locator: Locator,
  expected: string | readonly string[]
): Promise<void> {
  const want = typeof expected === 'string' ? collapse(expected) : expected.map(collapse);
  await test.step(
    `Expect the text ${JSON.stringify(want)}`,
    async () => {
      await expect
        .poll(async () => {
          const texts = (await locator.allInnerTexts()).map(collapse);
          return typeof want === 'string' ? texts.join(' ') : texts;
        })
        .toEqual(want);
    },
    { box: true }
  );
}
