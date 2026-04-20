#!/usr/bin/env node

/**
 * kindle-highlights.js
 *
 * Usage:
 *   node scripts/kindle-highlights.js "Book Title" /path/to/output/dir
 *
 * Before running for the first time, generate a saved session with:
 *   node scripts/kindle-login.js
 *
 * That saves .amazon-session.json in the project root.
 *
 * This script loads the saved session and runs fully headless. It never touches
 * Amazon's login flow. If the session has expired, re-run kindle-login.js to refresh it.
 */

import { chromium } from "playwright";
import { readFileSync, writeFileSync, mkdirSync, existsSync } from "fs";
import { resolve, join } from "path";
import { fileURLToPath } from "url";
import { dirname } from "path";

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);
const projectRoot = resolve(__dirname, "..");
const sessionPath = resolve(projectRoot, ".amazon-session.json");

// ---------------------------------------------------------------------------
// Parse CLI arguments
// ---------------------------------------------------------------------------
const [, , bookTitle, outputDir] = process.argv;

if (!bookTitle || !outputDir) {
  console.error(
    'Usage: node scripts/kindle-highlights.js "Book Title" /path/to/output/dir',
  );
  process.exit(1);
}

if (!existsSync(sessionPath)) {
  console.error(
    `No saved session found at ${sessionPath}.\nRun "node scripts/kindle-login.js" first to log in and save your session.`,
  );
  process.exit(1);
}

// Ensure output directory exists
const resolvedOutputDir = resolve(outputDir);
mkdirSync(resolvedOutputDir, { recursive: true });

// ---------------------------------------------------------------------------
// Main
// ---------------------------------------------------------------------------
(async () => {
  const browser = await chromium.launch({ headless: true });
  const context = await browser.newContext({
    userAgent:
      "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36",
    viewport: { width: 1280, height: 800 },
    // Load the previously saved authenticated session
    storageState: sessionPath,
  });

  const page = await context.newPage();

  try {
    // ------------------------------------------------------------------
    // 1. Navigate directly to Notes & Highlights (already logged in)
    // ------------------------------------------------------------------
    console.log("Navigating to Notes & Highlights …");
    await page.goto("https://read.amazon.com/notebook", {
      waitUntil: "domcontentloaded",
    });

    // If the session has expired Amazon will redirect to a login page
    if (!page.url().includes("read.amazon.com")) {
      throw new Error(
        `Session appears to have expired (redirected to ${page.url()}).\nRun "node scripts/kindle-login.js" to refresh your session.`,
      );
    }

    // ------------------------------------------------------------------
    // 2. Find the book in the library sidebar
    // ------------------------------------------------------------------
    console.log(`Looking for book: "${bookTitle}" …`);

    await page.waitForSelector("#library", { timeout: 20000 });

    // Try exact case-insensitive match first
    const escapedTitle = bookTitle.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
    const bookHeading = page
      .locator("#library h2")
      .filter({
        has: page.locator(`text=/^${escapedTitle}$/i`),
      })
      .first();

    let matchedHeading = bookHeading;
    if (!(await bookHeading.isVisible({ timeout: 5000 }).catch(() => false))) {
      console.log("Exact match not found, trying partial match …");
      const allHeadings = await page.locator("#library h2").all();
      let found = false;
      for (const h of allHeadings) {
        const text = await h.textContent();
        if (
          text &&
          text.trim().toLowerCase().includes(bookTitle.toLowerCase())
        ) {
          matchedHeading = h;
          found = true;
          console.log(`Matched book: "${text.trim()}"`);
          break;
        }
      }
      if (!found) {
        throw new Error(
          `Could not find a book matching "${bookTitle}" in the library.`,
        );
      }
    }

    await matchedHeading.click();
    console.log("Clicked book heading, waiting for highlights to load …");

    // ------------------------------------------------------------------
    // 3. Collect all highlights
    // ------------------------------------------------------------------
    await page.waitForSelector(".kp-notebook-highlight", { timeout: 15000 });

    const highlights = await page.$$eval(".kp-notebook-highlight", (els) =>
      els.map((el) => el.textContent?.trim()).filter(Boolean),
    );

    console.log(`Found ${highlights.length} highlight(s).`);

    // ------------------------------------------------------------------
    // 4. Write to JSON file
    // ------------------------------------------------------------------
    const outputFile = join(resolvedOutputDir, "highlights.json");
    writeFileSync(outputFile, JSON.stringify(highlights, null, 2), "utf8");
    console.log(`Highlights saved to: ${outputFile}`);
  } catch (err) {
    console.error("Error:", err.message);
    process.exit(1);
  } finally {
    await browser.close();
  }
})();
