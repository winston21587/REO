const { chromium } = require('c:/Users/janus/Herd/REO/.agents/skills/playwright-skill/node_modules/playwright');
const path = require('path');
const fs = require('fs');

(async () => {
    console.log('--- Starting /impeccable clarify visual verification ---');
    const browser = await chromium.launch({
        headless: true,
        args: ['--ignore-certificate-errors', '--no-sandbox']
    });

    const targetDir = path.resolve('c:/Users/janus/.gemini/antigravity-ide/brain/75331234-b238-4612-9357-e6859cfda27f/.tempmediaStorage');
    if (!fs.existsSync(targetDir)) {
        fs.mkdirSync(targetDir, { recursive: true });
    }

    const context = await browser.newContext({
        viewport: { width: 1440, height: 900 },
        ignoreHTTPSErrors: true
    });
    const page = await context.newPage();

    console.log('Logging in as admin...');
    await page.goto('https://reo.test/login');
    await page.fill('input[name="email"]', 'admin@reo.com');
    await page.fill('input[name="password"]', 'adminpassword');
    await Promise.all([
        page.waitForNavigation({ waitUntil: 'networkidle' }),
        page.click('button[type="submit"]')
    ]);

    // 1. Check main active protocols
    console.log('Visiting /admin/applications...');
    await page.goto('https://reo.test/admin/applications', { waitUntil: 'networkidle' });
    const mainShot = path.join(targetDir, 'clarify_active_protocols_desktop.png');
    await page.screenshot({ path: mainShot });
    console.log('Saved main shot:', mainShot);

    // 2. Open drawer to verify clarified labels
    const actionBtn = await page.$('table tbody tr:first-child button[title="Protocol Actions"]');
    if (actionBtn) {
        await actionBtn.click();
        await page.waitForTimeout(400);
        const drawerShot = path.join(targetDir, 'clarify_drawer_labels.png');
        await page.screenshot({ path: drawerShot });
        console.log('Saved drawer shot:', drawerShot);
        await page.keyboard.press('Escape');
        await page.waitForTimeout(300);
    }

    // 3. Test empty filter search state
    console.log('Testing filter search empty state...');
    await page.goto('https://reo.test/admin/applications?search=nonexistentprotocolquery12345', { waitUntil: 'networkidle' });
    const emptyFilterShot = path.join(targetDir, 'clarify_empty_filter_state.png');
    await page.screenshot({ path: emptyFilterShot });
    console.log('Saved empty filter shot:', emptyFilterShot);

    await browser.close();
    console.log('--- Verification finished ---');
})();
