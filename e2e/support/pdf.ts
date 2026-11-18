// The text of each page of a PDF file. TCPDF compresses the page contents, so
// a plain search of the file finds nothing.
export async function pdfPages(content: Buffer): Promise<string[]> {
  // unpdf is an ES module, and the specs are CommonJS, so it is loaded with a
  // dynamic import.
  const { extractText } = await import('unpdf');
  const { text } = await extractText(new Uint8Array(content), { mergePages: false });
  return text;
}
