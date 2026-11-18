import { dirname, join } from 'path';

// The text of each page of a PDF file. TCPDF compresses the page contents, so
// a plain search of the file finds nothing. The text runs of a page are
// joined with single spaces, so a table row reads as its cells in order, such
// as 'Ed Student 120.00 80.00'.
export async function pdfPages(content: Buffer): Promise<string[]> {
  // pdfjs-dist is an ES module, and the specs are CommonJS, so it is loaded
  // with a dynamic import.
  const { getDocument } = await import('pdfjs-dist/legacy/build/pdf.mjs');
  // The fonts that a PDF uses without embedding them, such as Helvetica.
  const standardFontDataUrl = join(
    dirname(require.resolve('pdfjs-dist/package.json')),
    'standard_fonts/'
  );
  const task = getDocument({ data: new Uint8Array(content), standardFontDataUrl });
  try {
    const pdf = await task.promise;
    const pages: string[] = [];
    for (let number = 1; number <= pdf.numPages; number++) {
      const page = await pdf.getPage(number);
      const { items } = await page.getTextContent();
      const text = items.map((item) => ('str' in item ? item.str : '')).join(' ');
      pages.push(text.replace(/\s+/g, ' ').trim());
    }
    return pages;
  } finally {
    await task.destroy();
  }
}
