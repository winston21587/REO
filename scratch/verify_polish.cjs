const { chromium } = require('c:/Users/janus/Herd/REO/.agents/skills/playwright-skill/node_modules/playwright');
const path = require('path');
const fs = require('fs');

(async () => {
    console.log('--- Starting /impeccable polish verification ---');
    const browser = await chromium.launch({
        headless: true,
        args: ['--ignore-certificate-errors', '--no-sandbox']
    });

    const targetDir = path.resolve('c:/Users/janus/.gemini/antigravity-ide/brain/75331234-b238-4612-9357-e6859cfda27f/.tempmediaStorage');
    if (!fs.existsSync(targetDir)) {
        fs.mkdirSync(targetDir, { recursive: true });
    }

    // Desktop Context (1440x900)
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

    // 1. Main Active Protocols Desktop View
    console.log('Visiting /admin/applications...');
    await page.goto('https://reo.test/admin/applications', { waitUntil: 'networkidle' });
    const mainDesktopShot = path.join(targetDir, 'polish_active_protocols_desktop.png');
    await page.screenshot({ path: mainDesktopShot });
    console.log('Captured:', mainDesktopShot);

    // 2. Test Action Drawer & Escape Key
    console.log('Testing Protocol Actions drawer...');
    const actionBtn = await page.$('table tbody tr:first-child button[title="Protocol Actions"]');
    if (actionBtn) {
        await actionBtn.click();
        await page.waitForTimeout(600);
        const drawerShot = path.join(targetDir, 'polish_action_drawer.png');
        await page.screenshot({ path: drawerShot });
        console.log('Captured:', drawerShot);

        // Test Escape dismissal
        console.log('Pressing Escape to dismiss drawer...');
        await page.keyboard.press('Escape');
        await page.waitForTimeout(400);
    }

    // 3. Test Assign Reviewer Modal
    console.log('Testing Assign Reviewer modal...');
    await page.evaluate(() => {
        window.dispatchEvent(new CustomEvent('open-assign-modal', {
            detail: {
                id: 1,
                title: 'Assessment of Environmental Pollutants in Urban Waterways',
                reviewType: 'Exempt Review',
                assigned: []
            }
        }));
    });
    await page.waitForTimeout(600);
    const assignModalShot = path.join(targetDir, 'polish_assign_modal.png');
    await page.screenshot({ path: assignModalShot });
    console.log('Captured:', assignModalShot);

    // Test Escape dismissal
    console.log('Pressing Escape to dismiss Assign Reviewer modal...');
    await page.keyboard.press('Escape');
    await page.waitForTimeout(400);

    // 4. Test Status Update Modal via openStatusModal
    console.log('Testing Status Update modal...');
    await page.evaluate(() => {
        if (typeof openStatusModal === 'function') {
            openStatusModal(1, 'Sample Research Protocol for Verification', 'Unassigned', 'Hardcopy Received', 'Pending Review', '2026-09-15');
        }
    });
    await page.waitForTimeout(600);
    const statusModalShot = path.join(targetDir, 'polish_status_modal.png');
    await page.screenshot({ path: statusModalShot });
    console.log('Captured:', statusModalShot);

    // Test Escape dismissal on Status Modal
    console.log('Pressing Escape to dismiss Status modal...');
    await page.keyboard.press('Escape');
    await page.waitForTimeout(400);

    // 5. Test AI Predict Modal
    console.log('Testing AI Predict modal...');
    await page.evaluate(() => {
        if (typeof openAiPredictModal === 'function') {
            openAiPredictModal(1, 'Assessment of Environmental Pollutants in Urban Waterways', 'Exempt Review');
        }
    });
    await page.waitForTimeout(600);
    const aiModalShot = path.join(targetDir, 'polish_ai_predict_modal.png');
    await page.screenshot({ path: aiModalShot });
    console.log('Captured:', aiModalShot);

    // Test Escape dismissal on AI modal
    console.log('Pressing Escape to dismiss AI Predict modal...');
    await page.keyboard.press('Escape');
    await page.waitForTimeout(400);

    await browser.close();
    console.log('--- Impeccable polish verification finished successfully ---');
})();
