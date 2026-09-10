/**
 * Pre-extracted Boneyard Bone Templates
 * Zero-layout-shift (CLS = 0) coordinate layouts for WMSU REO Portals.
 * Formatted for consumption by boneyard-js renderBones() or Boneyard engine.
 */

export const BoneyardTemplates = {
    // 1. GUEST / LANDING PAGE SKELETON
    landing: {
        width: 1200,
        height: 720,
        bones: [
            // Hero Title & Subtext
            { x: 6, y: 140, w: 22, h: 28, r: 14 },   // REO Pill Badge
            { x: 6, y: 184, w: 55, h: 56, r: 8 },    // Hero Main Heading Line 1
            { x: 6, y: 248, w: 42, h: 48, r: 8 },    // Hero Main Heading Line 2
            { x: 6, y: 310, w: 50, h: 20, r: 6 },    // Subtitle Line 1
            { x: 6, y: 338, w: 38, h: 20, r: 6 },    // Subtitle Line 2
            { x: 6, y: 385, w: 16, h: 48, r: 12 },   // Primary CTA Button
            { x: 23, y: 385, w: 16, h: 48, r: 12 },  // Secondary CTA Button
            
            // Hero Bottom Features / Highlights Ribbon
            { x: 6, y: 520, w: 28, h: 140, r: 16 },  // Highlight Card 1
            { x: 36, y: 520, w: 28, h: 140, r: 16 }, // Highlight Card 2
            { x: 66, y: 520, w: 28, h: 140, r: 16 }, // Highlight Card 3
        ]
    },

    // 2. RESEARCHER DASHBOARD SKELETON
    researcher: {
        width: 1200,
        height: 780,
        bones: [
            // Top Welcome Header
            { x: 0, y: 16, w: 30, h: 36, r: 8 },     // "Welcome back, Researcher!"
            { x: 0, y: 58, w: 40, h: 18, r: 6 },     // "Here is the status of your research..."
            { x: 82, y: 20, w: 18, h: 44, r: 12 },   // "New Submission" Button
            
            // 3-Card Metric Summary Ribbon
            { x: 0, y: 96, w: 31, h: 108, r: 16 },   // Stat Card 1 (Active / Drafts)
            { x: 34.5, y: 96, w: 31, h: 108, r: 16 }, // Stat Card 2 (Under Review)
            { x: 69, y: 96, w: 31, h: 108, r: 16 },  // Stat Card 3 (Approved / Completed)
            
            // Submissions List Header
            { x: 0, y: 230, w: 25, h: 24, r: 6 },    // "Your Submissions"
            { x: 75, y: 224, w: 25, h: 38, r: 10 },  // Search / Filter Input
            
            // Submission Item Cards
            { x: 0, y: 280, w: 100, h: 130, r: 16 }, // Submission Card 1
            { x: 0, y: 426, w: 100, h: 130, r: 16 }, // Submission Card 2
            { x: 0, y: 572, w: 100, h: 130, r: 16 }, // Submission Card 3
        ]
    },

    // 3. ADMIN & SUPER ADMIN DASHBOARD / LEDGER TABLES
    admin: {
        width: 1200,
        height: 820,
        bones: [
            // Top Header & Page Title
            { x: 0, y: 10, w: 24, h: 32, r: 8 },     // Page Title (e.g., Protocol Oversight)
            { x: 0, y: 48, w: 35, h: 16, r: 6 },     // Subtitle
            { x: 80, y: 12, w: 20, h: 40, r: 10 },   // Header Quick Action

            // 5-Card Metric Ribbon
            { x: 0, y: 80, w: 18.5, h: 96, r: 14 },     // Stat 1: Total Submissions
            { x: 20.3, y: 80, w: 18.5, h: 96, r: 14 },  // Stat 2: Active Protocols
            { x: 40.6, y: 80, w: 18.5, h: 96, r: 14 },  // Stat 3: Revisions
            { x: 60.9, y: 80, w: 18.5, h: 96, r: 14 },  // Stat 4: Certifications
            { x: 81.2, y: 80, w: 18.8, h: 96, r: 14 },  // Stat 5: Revenue Logs

            // Table Filter / Search Controls Bar
            { x: 0, y: 194, w: 35, h: 42, r: 10 },   // Search Bar
            { x: 37, y: 194, w: 18, h: 42, r: 10 },  // Status Dropdown
            { x: 57, y: 194, w: 18, h: 42, r: 10 },  // Category Dropdown
            { x: 85, y: 194, w: 15, h: 42, r: 10 },  // Export / Action Button

            // Ledger Data Table Wrapper
            { x: 0, y: 254, w: 100, h: 48, r: 8 },   // Table Header Row
            { x: 0, y: 310, w: 100, h: 62, r: 6 },   // Table Data Row 1
            { x: 0, y: 380, w: 100, h: 62, r: 6 },   // Table Data Row 2
            { x: 0, y: 450, w: 100, h: 62, r: 6 },   // Table Data Row 3
            { x: 0, y: 520, w: 100, h: 62, r: 6 },   // Table Data Row 4
            { x: 0, y: 590, w: 100, h: 62, r: 6 },   // Table Data Row 5
            { x: 0, y: 660, w: 100, h: 62, r: 6 },   // Table Data Row 6

            // Table Pagination Footer
            { x: 0, y: 740, w: 22, h: 24, r: 6 },    // "Showing 1 to 7 of 22"
            { x: 78, y: 736, w: 22, h: 36, r: 10 },  // Pagination buttons
        ]
    },

    // 4. REVIEWER PORTAL SKELETON
    reviewer: {
        width: 1200,
        height: 760,
        bones: [
            // Top Reviewer Header
            { x: 0, y: 12, w: 28, h: 32, r: 8 },     // "Assigned Protocols"
            { x: 0, y: 50, w: 42, h: 16, r: 6 },     // "Review the research protocols assigned..."
            { x: 84, y: 16, w: 16, h: 36, r: 18 },   // Protocols Count Pill Badge

            // Review Protocol Grid (4 Cards across)
            { x: 0, y: 90, w: 23.5, h: 240, r: 16 },    // Review Card 1
            { x: 25.5, y: 90, w: 23.5, h: 240, r: 16 }, // Review Card 2
            { x: 51, y: 90, w: 23.5, h: 240, r: 16 },   // Review Card 3
            { x: 76.5, y: 90, w: 23.5, h: 240, r: 16 }, // Review Card 4

            // Second Row of Review Protocol Cards
            { x: 0, y: 350, w: 23.5, h: 240, r: 16 },    // Review Card 5
            { x: 25.5, y: 350, w: 23.5, h: 240, r: 16 }, // Review Card 6
            { x: 51, y: 350, w: 23.5, h: 240, r: 16 },   // Review Card 7
            { x: 76.5, y: 350, w: 23.5, h: 240, r: 16 }, // Review Card 8
        ]
    }
};
