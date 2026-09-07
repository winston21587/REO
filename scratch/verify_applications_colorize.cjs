const { chromium } = require('c:/Users/janus/Herd/REO/.agents/skills/playwright-skill/node_modules/playwright');
const path = require('path');
const fs = require('fs');

(async () => {
    console.log('--- Starting /impeccable colorize visual verification on Active Protocols ---');
    const browser = await chromium.launch({
        headless: true,
        args: ['--ignore-certificate-errors', '--no-sandbox']
    });

    const targetDir = path.resolve('c:/Users/janus/.gemini/antigravity-ide/brain/75331234-b238-4612-9357-e6859cfda27f/.tempmediaStorage');
    if (!fs.existsSync(targetDir)) {
        fs.mkdirSync(targetDir, { recursive: true });
    }

    // 1. Desktop test
    const desktopContext = await browser.newContext({
        viewport: { width: 1440, height: 900 },
        ignoreHTTPSErrors: true
    });
    const page = await desktopContext.newPage();

    console.log('Logging in as admin...');
    await page.goto('https://reo.test/login');
    await page.fill('input[name="email"]', 'admin@reo.com');
    await page.fill('input[name="password"]', 'adminpassword');
    await Promise.all([
        page.waitForNavigation({ waitUntil: 'networkidle' }),
        page.click('button[type="submit"]')
    ]);

    console.log('Navigating to /admin/applications...');
    await page.goto('https://reo.test/admin/applications', { waitUntil: 'networkidle' });

    const desktopShotPath = path.join(targetDir, 'active_protocols_desktop_colorized.png');
    await page.screenshot({ path: desktopShotPath });
    console.log('Desktop screenshot saved:', desktopShotPath);

    // 2. Open action drawer
    console.log('Testing action drawer...');
    const actionBtn = await page.$('table tbody tr:first-child button[title="Protocol Actions"]');
    if (actionBtn) {
        await actionBtn.click();
        await page.waitForTimeout(500);
        const drawerShotPath = path.join(targetDir, 'active_protocols_drawer_colorized.png');
        await page.screenshot({ path: drawerShotPath });
        console.log('Drawer screenshot saved:', drawerShotPath);
    }

    // 3. Mobile test (390x844 iPhone 14)
    const mobileContext = await browser.newContext({
        viewport: { width: 390, height: 844 },
        ignoreHTTPSErrors: true,
        isMobile: true
    });
    const mobilePage = await mobileContext.newPage();
    await mobilePage.goto('https://reo.test/login');
    await mobilePage.fill('input[name="email"]', 'admin@reo.com');
    await mobilePage.fill('input[name="password"]', 'adminpassword');
    await Promise.all([
        mobilePage.waitForNavigation({ waitUntil: 'networkidle' }),
        mobilePage.click('button[type="submit"]')
    ]);
    await mobilePage.goto('https://reo.test/admin/applications', { waitUntil: 'networkidle' });
    const mobileShotPath = path.join(targetDir, 'active_protocols_mobile_colorized.png');
    await mobilePage.screenshot({ path: mobileShotPath });
    console.log('Mobile screenshot saved:', mobileShotPath);

    await browser.close();
    console.log('--- Verification completed successfully ---');
})();
