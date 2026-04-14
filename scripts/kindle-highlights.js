#!/usr/bin/env node

/**
 * kindle-highlights.js
 *
 * Usage:
 *   node scripts/kindle-highlights.js "Book Title" /path/to/output/dir
 *
 * Credentials are read from the project .env file:
 *   AMAZON_EMAIL=your@email.com
 *   AMAZON_PASSWORD=yourpassword
 *
 * Requires:
 *   npm install playwright dotenv
 *   npx playwright install chromium
 */

import { chromium, firefox } from "playwright";
import { readFileSync, writeFileSync, mkdirSync } from "fs";
import { resolve, join } from "path";
import { fileURLToPath } from "url";
import { dirname } from "path";

// ---------------------------------------------------------------------------
// Load .env from the project root (one level up from /scripts)
// ---------------------------------------------------------------------------
const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);
const envPath = resolve(__dirname, "../.env");

function loadEnv(path) {
  try {
    const lines = readFileSync(path, "utf8").split("\n");
    for (const line of lines) {
      const trimmed = line.trim();
      if (!trimmed || trimmed.startsWith("#")) continue;
      const eqIndex = trimmed.indexOf("=");
      if (eqIndex === -1) continue;
      const key = trimmed.slice(0, eqIndex).trim();
      const value = trimmed
        .slice(eqIndex + 1)
        .trim()
        .replace(/^["']|["']$/g, "");
      if (key && !(key in process.env)) {
        process.env[key] = value;
      }
    }
  } catch {
    // .env not found — rely on environment variables already set
  }
}

loadEnv(envPath);

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

const email = process.env.AMAZON_EMAIL;
const password = process.env.AMAZON_PASSWORD;

if (!email || !password) {
  console.error(
    "AMAZON_EMAIL and AMAZON_PASSWORD must be set in .env or environment.",
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
  const browser = await chromium.launch({ headless: false });
  const userAgent =
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36";
  const context = await browser.newContext();
  const page = await context.newPage();

  try {
    // ------------------------------------------------------------------
    // 1. Navigate to Kindle Cloud Reader and log in
    // ------------------------------------------------------------------
    console.log("Navigating to read.amazon.com …");
    await page.goto("https://read.amazon.com/", {
      waitUntil: "domcontentloaded",
    });

    // Amazon may redirect to a sign-in page or show a sign-in button
    // Handle both cases
    const signInButton = page
      .locator('a[href*="signin"], a:has-text("Sign in"), #top-sign-in-btn')
      .first();

    if (await signInButton.isVisible({ timeout: 5000 }).catch(() => false)) {
      console.log("Clicking sign-in link …");
      await signInButton.click();
    }

    // Fill in email
    await page.waitForSelector("#ap_email", { timeout: 15000 });
    console.log("Entering email …");
    await page.fill("#ap_email", email);

    // Some Amazon sign-in flows have a separate "Continue" button before password
    const continueBtn = page.locator("input#continue");
    if (await continueBtn.isVisible({ timeout: 2000 }).catch(() => false)) {
      await continueBtn.click();
    }

    await page.waitForSelector("#ap_password", { timeout: 10000 });
    console.log("Entering password …");
    await page.fill("#ap_password", password);
    await page.click("#signInSubmit");

    // Wait for successful login — the library iframe or main content should appear
    console.log("Waiting for login to complete …");
    await page.waitForURL(/read\.amazon\.com/, { timeout: 30000 });
    // Give the page a moment to settle (MFA prompts etc. are handled manually
    // when running in headed mode)
    await page.waitForTimeout(10000);

    // ------------------------------------------------------------------
    // 2. Navigate to Notes & Highlights
    // ------------------------------------------------------------------
    console.log("Navigating to Notes & Highlights …");
    // await page.waitForSelector("#notes_title", { timeout: 30000 });
    await page.goto("https://read.amazon.com/notebook", {
      waitUntil: "domcontentloaded",
    });
    await page.screenshot({ path: "test.png", fullPage: true });
    const text = (await page.textContent("body"))
      .replace(/ +/g, " ")
      .replace(/(\n ?)+/g, "\n")
      .trim();
    console.log(text);
    await page.waitForTimeout(2000);

    // ------------------------------------------------------------------
    // 3. Find the book in the library sidebar
    // ------------------------------------------------------------------
    console.log(`Looking for book: "${bookTitle}" …`);

    // Wait for the #library element to be present
    await page.waitForSelector("#library", { timeout: 20000 });

    // Find an h2 inside #library whose text matches (case-insensitive)
    const bookHeading = page
      .locator("#library h2")
      .filter({
        has: page.locator(
          `text=/^${bookTitle.replace(/[.*+?^${}()|[\]\\]/g, "\\$&")}$/i`,
        ),
      })
      .first();

    // Fall back to partial match if exact match not found
    let matchedHeading = bookHeading;
    if (!(await bookHeading.isVisible({ timeout: 5000 }).catch(() => false))) {
      console.log(
        "Exact match not found, trying partial/case-insensitive match …",
      );
      // Grab all h2s inside #library and find one that matches case-insensitively
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
    await page.waitForTimeout(3000);

    // ------------------------------------------------------------------
    // 4. Collect all highlights
    // ------------------------------------------------------------------
    await page.waitForSelector(".kp-notebook-highlight", { timeout: 15000 });

    const highlights = await page.$$eval(".kp-notebook-highlight", (els) =>
      els.map((el) => el.textContent?.trim()).filter(Boolean),
    );

    console.log(`Found ${highlights.length} highlight(s).`);

    // ------------------------------------------------------------------
    // 5. Write to JSON file
    // ------------------------------------------------------------------
    const safeTitle = bookTitle.replace(/[^a-z0-9]+/gi, "-").toLowerCase();
    const outputFile = join(resolvedOutputDir, `highlights.json`);

    writeFileSync(outputFile, JSON.stringify(highlights, null, 2), "utf8");

    console.log(`Highlights saved to: ${outputFile}`);
  } catch (err) {
    console.error("Error:", err.message);
    process.exit(1);
  } finally {
    await browser.close();
  }
})();
