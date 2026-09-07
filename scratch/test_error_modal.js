import { chromium } from 'playwright';
import path from 'path';

async function run() {
    const browser = await chromium.launch({ 
        headless: true,
        channel: 'msedge'
    });
    const context = await browser.newContext({
        ignoreHTTPSErrors: true,
        viewport: { width: 1440, height: 900 }
    });
    const page = await context.newPage();

    console.log('Navigating to dev login...');
    await page.goto('https://reo.test/dev-login-researcher', { waitUntil: 'networkidle' });

    // Simulate 429 Rate Limit
    console.log('Triggering 429 Rate Limit UI...');
    await page.evaluate(() => {
        const fake429 = {
            error: "Hourly submission limit reached. Please try again in 1 hour.",
            retry_after: 3600
        };
        showSubmissionErrorModal(fake429, 429);
    });

    await page.waitForTimeout(500);

    const artifactDir = 'C:/Users/janus/.gemini/antigravity-ide/brain/75331234-b238-4612-9357-e6859cfda27f';
    const quotaErrScreenshot = path.join(artifactDir, 'submission_quota_error_preview.png');
    await page.screenshot({ path: quotaErrScreenshot });
    console.log('Saved quota error screenshot to:', quotaErrScreenshot);

    await browser.close();
}

run().catch(console.error);
