const { chromium } = require('c:/Users/janus/Herd/REO/.agents/skills/playwright-skill/node_modules/playwright');

(async () => {
    console.log('--- Starting /impeccable quieter & small verification on Initial Intake ---');
    const browser = await chromium.launch({
        headless: true,
        args: ['--ignore-certificate-errors', '--no-sandbox']
    });

    // Test viewports: 1440x900 (desktop), 1366x768 (typical laptop), 1280x720 (compact)
    const viewports = [
        { name: 'desktop_1440x900', width: 1440, height: 900 },
        { name: 'laptop_1366x768', width: 1366, height: 768 },
        { name: 'compact_1280x720', width: 1280, height: 720 },
        { name: 'mobile_390x844', width: 390, height: 844 },
    ];

    for (const vp of viewports) {
        const context = await browser.newContext({
            viewport: { width: vp.width, height: vp.height },
            ignoreHTTPSErrors: true
        });
        const page = await context.newPage();

        // 1. Login
        await page.goto('https://reo.test/login');
        await page.fill('input[name="email"]', 'admin@reo.com');
        await page.fill('input[name="password"]', 'adminpassword');
        await Promise.all([
            page.waitForNavigation({ waitUntil: 'networkidle' }),
            page.click('button[type="submit"]')
        ]);

        // 2. Navigate to /admin/new
        await page.goto('https://reo.test/admin/new', { waitUntil: 'networkidle' });

        // 3. Measure dimensions
        const measurements = await page.evaluate(() => {
            const scrollContainer = document.querySelector('main > div.overflow-y-auto') || document.querySelector('main');
            const recentCol = document.getElementById('recent-column');
            const incompleteCol = document.getElementById('incomplete-column');
            const cards = Array.from(document.querySelectorAll('#recent-submissions-container .group'));
            
            const cardHeights = cards.map(c => Math.round(c.getBoundingClientRect().height));
            const containerHeight = scrollContainer ? scrollContainer.clientHeight : window.innerHeight;
            const contentScrollHeight = scrollContainer ? scrollContainer.scrollHeight : document.body.scrollHeight;
            const needsScroll = contentScrollHeight > containerHeight;

            return {
                windowHeight: window.innerHeight,
                containerClientHeight: containerHeight,
                containerScrollHeight: contentScrollHeight,
                needsScroll: needsScroll,
                recentColHeight: recentCol ? Math.round(recentCol.getBoundingClientRect().height) : 0,
                incompleteColHeight: incompleteCol ? Math.round(incompleteCol.getBoundingClientRect().height) : 0,
                cardCount: cards.length,
                cardHeights: cardHeights,
                avgCardHeight: cardHeights.length > 0 ? (cardHeights.reduce((a,b) => a+b, 0) / cardHeights.length).toFixed(1) : 0
            };
        });

        console.log(`\nViewport [${vp.name}]:`, JSON.stringify(measurements, null, 2));

        // Take screenshot of the view
        const shotPath = `C:/Users/janus/.gemini/antigravity-ide/brain/75331234-b238-4612-9357-e6859cfda27f/${vp.name}_fitscreen.png`;
        await page.screenshot({ path: shotPath, fullPage: false });
        console.log(`Saved screenshot: ${shotPath}`);

        // If desktop, test modal open
        if (vp.width === 1440) {
            const triageBtn = await page.$('.triage-trigger-btn');
            if (triageBtn) {
                await triageBtn.click();
                await page.waitForTimeout(400);
                const modalVisible = await page.$eval('#triageModal', el => !el.classList.contains('hidden'));
                console.log('Triage modal opens successfully:', modalVisible);
                const modalShotPath = `C:/Users/janus/.gemini/antigravity-ide/brain/75331234-b238-4612-9357-e6859cfda27f/desktop_triage_modal_wide.png`;
                await page.screenshot({ path: modalShotPath, fullPage: false });
                console.log(`Saved triage modal screenshot: ${modalShotPath}`);
                await page.click('button[onclick="closeTriage()"]');
                await page.waitForTimeout(350);
            }
        }

        await context.close();
    }

    await browser.close();
    console.log('\n=== VERIFICATION COMPLETED SUCCESSFULLY ===');
})();
