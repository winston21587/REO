const { chromium } = require('c:/Users/janus/Herd/REO/.agents/skills/playwright-skill/node_modules/playwright');
const path = require('path');
const fs = require('fs');

(async () => {
    console.log('--- Starting /impeccable quieter visual verification ---');
    const browser = await chromium.launch({
        headless: true,
        args: ['--ignore-certificate-errors', '--no-sandbox']
    });

    const targetDir = path.resolve('c:/Users/janus/.gemini/antigravity-ide/brain/75331234-b238-4612-9357-e6859cfda27f/.tempmediaStorage');
    if (!fs.existsSync(targetDir)) {
        fs.mkdirSync(targetDir, { recursive: true });
    }

    // 1. Desktop context (1440x900)
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

    // Test 1: Main Active Protocols Desktop Table
    console.log('Visiting /admin/applications (Desktop Quieter Table)...');
    await page.goto('https://reo.test/admin/applications', { waitUntil: 'networkidle' });
    const mainTableShot = path.join(targetDir, 'quieter_active_protocols_desktop.png');
    await page.screenshot({ path: mainTableShot });
    console.log('Saved quieter main table shot:', mainTableShot);

    // Test 2: Open Action Drawer to verify quieter headers and buttons
    const actionBtn = await page.$('table tbody tr:first-child button[title="Protocol Actions"]');
    if (actionBtn) {
        console.log('Opening action drawer...');
        await actionBtn.click();
        await page.waitForTimeout(600);
        const drawerShot = path.join(targetDir, 'quieter_drawer_calm.png');
        await page.screenshot({ path: drawerShot });
        console.log('Saved quieter drawer shot:', drawerShot);
        await page.keyboard.press('Escape');
        await page.waitForTimeout(400);
    } else {
        console.log('Action button not found on first row');
    }

    // Test 3: Filter Chips Bar (Cohesive Slate Neutral)
    console.log('Visiting /admin/applications with filters...');
    await page.goto('https://reo.test/admin/applications?review_types%5B%5D=Exempt+Review&doc_statuses%5B%5D=Hardcopy+Received', { waitUntil: 'networkidle' });
    const filterChipsShot = path.join(targetDir, 'quieter_filter_chips_slate.png');
    await page.screenshot({ path: filterChipsShot });
    console.log('Saved quieter filter chips shot:', filterChipsShot);

    // Test 4: Mobile Adaptive Cards View (390x844)
    console.log('Testing mobile cards viewport (390x844)...');
    const mobileContext = await browser.newContext({
        viewport: { width: 390, height: 844 },
        ignoreHTTPSErrors: true,
        isMobile: true,
        hasTouch: true
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
    const mobileShot = path.join(targetDir, 'quieter_active_protocols_mobile.png');
    await mobilePage.screenshot({ path: mobileShot });
    console.log('Saved quieter mobile shot:', mobileShot);

    await browser.close();
    console.log('--- Quieter verification complete ---');
})();
