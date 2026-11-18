// Makes the snapshots of the trace viewer (UI mode, show-trace) scroll to the
// highlighted element of a step when it is outside the visible part of the
// page. Playwright keeps the scroll position of the moment of the snapshot,
// and checks such as expect() do not scroll, so the highlight is often below
// the fold.
//
// It changes the installed Playwright package, so npm runs it after each
// install (postinstall in package.json). Reload the UI page afterwards, so the
// browser loads the new service worker.
//
// Only the viewer changes, not the browser or the test runner. So when
// Playwright changes the code that this patches, for example after an
// upgrade, the script only warns: the tests still run, and the anchor below
// needs an update, or the script can go.

import { readFileSync, writeFileSync } from 'fs';
import { createRequire } from 'module';
import path from 'path';

const require = createRequire(import.meta.url);
const bundle = path.join(
  path.dirname(require.resolve('playwright-core/package.json')),
  'lib/vite/traceViewer/sw.bundle.js'
);

const MARKER = '/*ipeer-scroll-to-target*/';
// The end of the snapshot script's scroll restore. The highlighted elements
// are in `u`, the window of the snapshot in `r`.
const ANCHOR = 'r.document.styleSheets[0].disabled=!0;';
const SCROLL =
  `${MARKER}(()=>{let t=u[0];if(!t)return;let b=t.getBoundingClientRect();` +
  `if(b.top<0||b.bottom>r.innerHeight)t.scrollIntoView({block:'center'})})();`;

function warn(message) {
  console.warn(`trace-viewer-scroll: ${message} The trace viewer does not scroll to the target.`);
}

let source;
try {
  source = readFileSync(bundle, 'utf8');
} catch (error) {
  warn(`Cannot read ${bundle} (${error.code}).`);
  process.exit(0);
}
if (source.includes(MARKER)) {
  console.log('trace-viewer-scroll: already patched.');
} else {
  const count = source.split(ANCHOR).length - 1;
  if (count === 1) {
    writeFileSync(bundle, source.replace(ANCHOR, ANCHOR + SCROLL));
    console.log('trace-viewer-scroll: the trace viewer now scrolls to the target.');
  } else {
    warn(`Found the anchor ${count} times in ${bundle}, expected once.`);
  }
}
