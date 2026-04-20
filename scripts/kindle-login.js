#!/usr/bin/env node

/**
 * kindle-login.js
 *
 * Run this ONCE to log in to Amazon in a visible browser window and save the
 * authenticated session to disk. Subsequent runs of kindle-highlights.js will
 * load that saved session and run fully headless without touching the login flow.
 *
 * Usage:
 *   node scripts/kindle-login.js
 *
 * Credentials are read from the project .env file:
 *   AMAZON_EMAIL=your@email.com
 *   AMAZON_PASSWORD=yourpassword
 *
 * The session is saved to .amazon-session.json in the project root.
 * Add that file to .gitignore — it contains your auth cookies.
 */

import { chromium } from "playwright";
import { readFileSync, writeFileSync } from "fs";
import { resolve } from "path";
import { fileURLToPath } from "url";
import { dirname } from "path";

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);
const projectRoot = resolve(__dirname, "..");
const envPath = resolve(projectRoot, ".env");
const sessionPath = resolve(projectRoot, ".amazon-session.json");

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
      if (key && !(key in process.env)) process.env[key] = value;
    }
  } catch {
    // rely on existing env
  }
}

loadEnv(envPath);

const email = process.env.AMAZON_EMAIL;
const password = process.env.AMAZON_PASSWORD;

if (!email || !password) {
  console.error("AMAZON_EMAIL and AMAZON_PASSWORD must be set in .env or environment.");
  process.exit(1);
}

(async () => {
  const browser = await chromium.launch({ headless: false });
  const context = await browser.newContext({
    userAgent:
      "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36",
    viewport: { width: 1280, height: 800 },
  });

  const page = await context.newPage();

  console.log("Navigating to read.amazon.com …");
  await page.goto("https://read.amazon.com/", { waitUntil: "domcontentloaded" });

  // Click sign-in link if present
  const signInButton = page
    .locator('a[href*="signin"], a:has-text("Sign in"), #top-sign-in-btn')
    .first();
  if (await signInButton.isVisible({ timeout: 5000 }).catch(() => false)) {
    await signInButton.click();
  }

  await page.waitForSelector("#ap_email", { timeout: 15000 });
  console.log("Entering email …");
  await page.fill("#ap_email", email);

  const continueBtn = page.locator("input#continue");
  if (await continueBtn.isVisible({ timeout: 2000 }).catch(() => false)) {
    await continueBtn.click();
  }

  await page.waitForSelector("#ap_password", { timeout: 10000 });
  console.log("Entering password …");
  await page.fill("#ap_password", password);
  await page.click("#signInSubmit");

  console.log("Waiting for login to complete …");
  // Wait until we land back on read.amazon.com (not an /ap/ auth path)
  await page.waitForURL((url) => url.hostname === "read.amazon.com" && !url.pathname.startsWith("/ap/"), {
    timeout: 60000,
  });

  // Give the page a moment to set all post-login cookies
  await page.waitForTimeout(2000);

  // Save cookies + localStorage
  const storageState = await context.storageState();
  writeFileSync(sessionPath, JSON.stringify(storageState, null, 2), "utf8");

  console.log(`Session saved to ${sessionPath}`);
  console.log("You can now run kindle-highlights.js in headless mode.");

  await browser.close();
})();
