# Panel Dashboard Customization Plan

**Status**: Planning Phase
**Branch**: `optimizations`
**Goal**: Create custom Panel dashboard with 2-column responsive layout and custom sections
**Purpose**: Portfolio piece demonstrating scalable Panel customization

---

## Layout Design

### Desktop Layout (2x2 Grid)
```
________________________________
|       1       |       2       |
|   Greeting    | Recent Posts  |
|   Actions     | Projects      |
|_______________|_______________|
|       3       |       4       |
|   Pages       |   (Future)    |
|   List        |               |
|_______________|_______________|
```

### Mobile Layout (Single Column Stack)
```
_________________
|       1       |
|   Greeting    |
|   Actions     |
|_______________|
|       2       |
| Recent Posts  |
| Projects      |
|_______________|
|       3       |
|   Pages       |
|   List        |
|_______________|
|       4       |
|   (Future)    |
|_______________|
```

---

## Section Details

### Section 1: Greeting & Actions

**Components:**
1. **Greeting Widget**
   - Dynamic time-based greeting: "Good morning/afternoon/evening"
   - User's first name from `$kirby->user()->name()->first()`
   - Typography matching public site (distinctive detail)

2. **Action Buttons**
   - "New Post" - Creates new article under `/posts`
   - "New Project" - Creates new project sub-page
   - Large, mobile-friendly buttons (HStack layout)

**Technical Approach:**
- Custom Panel section: `greeting`
- PHP: Calculate time of day, fetch user info
- Vue component: Render greeting + buttons
- CSS: Typography + button styling

---

### Section 2: Recent Content

**Components:**
1. **Recent Posts (VStack)**
   - 2 most recent published posts (for typo fixes)
   - Up to 3 draft posts (most recent first)
   - "More" link → `/panel/pages/posts`

2. **Recent Projects**
   - 3 most recent project sub-pages by publish date
   - "More" link → `/panel/pages/projects`

**Technical Approach:**
- Custom Panel section: `recentPosts`
- Custom Panel section: `recentProjects`
- PHP: Query content, format for display
- Use Kirby's built-in `pages` section rendering for consistency

---

### Section 3: Pages List

**Components:**
- Compact list of all site pages
- Uses Kirby's standard `pages` section
- Contained in one quadrant instead of full-width

**Technical Approach:**
- Standard `pages` section type
- Styled to fit within grid layout

---

### Section 4: Future Placeholder

**Components:**
- Empty for now
- Reserved for future expansion (analytics, stats, etc.)

**Technical Approach:**
- Info section with placeholder text
- Or omit from initial implementation

---

## Implementation Phases

### Phase 1: Setup & Plugin Structure (15 min)

**Tasks:**
1. Extend `site/plugins/site/index.php` with Panel section extensions
2. Create directory structure:
   ```
   site/plugins/site/
   ├── index.php (extend with sections)
   ├── sections/
   │   ├── greeting.php
   │   ├── recentPosts.php
   │   └── recentProjects.php
   └── index.css (Panel CSS)
   ```
3. Register sections in plugin

**Deliverables:**
- Plugin structure ready
- Sections registered (empty for now)

---

### Phase 2: Greeting & Actions Section (45 min)

**Tasks:**
1. Create `sections/greeting.php`:
   - Calculate time of day (morning/afternoon/evening)
   - Fetch user's first name
   - Return data for Vue component

2. Create greeting section template:
   - Render greeting with custom typography
   - Add two action buttons

3. Add CSS for typography matching public site

4. Wire up button actions:
   - "New Post" → `panel.dialog.open` to create article
   - "New Project" → `panel.dialog.open` to create project

**Testing:**
- Greeting displays correctly with time
- User name appears
- Buttons open correct dialogs
- Typography matches public site

**Deliverables:**
- Working greeting section
- Functional action buttons

---

### Phase 3: Recent Content Sections (1 hour)

**Tasks:**
1. Create `sections/recentPosts.php`:
   - Query 2 most recent published posts
   - Query up to 3 draft posts
   - Format as list with edit links
   - Add "More" link

2. Create `sections/recentProjects.php`:
   - Query 3 most recent project sub-pages
   - Format as list with edit links
   - Add "More" link

3. Style sections to match Kirby's native section styling

**Testing:**
- Recent posts display correctly
- Drafts appear when present
- Recent projects display
- All links navigate correctly

**Deliverables:**
- Working recent posts section
- Working recent projects section

---

### Phase 4: Layout & Responsive Grid (45 min)

**Tasks:**
1. Update `site/blueprints/site.yml`:
   ```yaml
   title: Site

   columns:
     - width: 1/2
       sections:
         greeting:
           type: greeting
           headline: Welcome

         pages:
           type: pages
           headline: Pages
           layout: list

     - width: 1/2
       sections:
         recentPosts:
           type: recentPosts
           headline: Recent Activity

         recentProjects:
           type: recentProjects
           headline: Recent Projects

         placeholder:
           type: info
           text: Future expansion area
   ```

2. Add Panel CSS in `site/plugins/site/index.css`:
   - Desktop: 2-column grid
   - Mobile: Single column stack
   - Ensure Kirby's Panel styles aren't overridden

3. Test responsive behavior

**Testing:**
- Desktop shows 2x2 grid
- Mobile stacks to single column
- All sections remain functional
- No layout breaking

**Deliverables:**
- Complete 2-column responsive layout
- All sections integrated

---

### Phase 5: Testing & Polish (30 min)

**Tasks:**
1. Cross-browser testing (Chrome, Firefox, Safari)
2. Mobile device testing (iOS, Android)
3. Performance check:
   - Dashboard load time
   - Query performance for recent content
4. Accessibility check:
   - Keyboard navigation
   - Screen reader compatibility
5. Add inline documentation to code
6. Create README for plugin

**Testing Checklist:**
- [ ] Greeting displays correctly at different times of day
- [ ] User name appears
- [ ] Action buttons work
- [ ] Recent posts load
- [ ] Drafts appear when present
- [ ] Recent projects load
- [ ] All links work
- [ ] Desktop 2-column layout renders
- [ ] Mobile single-column layout renders
- [ ] Typography matches public site
- [ ] No console errors
- [ ] Fast load time (<500ms)

**Deliverables:**
- Fully tested dashboard
- Documentation complete

---

## Technical Architecture

### Plugin Structure

**File: `site/plugins/site/index.php`**
```php
<?php

Kirby::plugin('brianjgoodwin/site', [
    // Existing cache invalidation hooks...

    // Panel sections
    'sections' => [
        'greeting' => require __DIR__ . '/sections/greeting.php',
        'recentPosts' => require __DIR__ . '/sections/recentPosts.php',
        'recentProjects' => require __DIR__ . '/sections/recentProjects.php',
    ],

    // Panel CSS
    'snippets' => [
        'panel.css' => __DIR__ . '/index.css'
    ]
]);
```

### Section API Structure

Each section file returns an array with:
```php
return [
    'props' => [
        // Define configurable properties
    ],
    'computed' => [
        // Calculate data for display
        'data' => function() {
            // Query content, process, return
        }
    ]
];
```

### Blueprint Configuration

**File: `site/blueprints/site.yml`**
- Use `columns` with `width: 1/2` for 2-column layout
- Define sections within each column
- Kirby handles responsive stacking automatically

---

## Potential Pitfalls & Solutions

### 1. Vue Version Compatibility
**Issue**: Kirby is migrating to Vue 3 (shipping in Kirby 6.0)
**Solution**: Use Kirby's built-in section rendering where possible, minimal custom Vue

### 2. Panel CSS Specificity
**Issue**: Custom CSS might conflict with Panel styles
**Solution**: Scope CSS carefully, test thoroughly, use Panel's CSS variables

### 3. Cache Invalidation
**Issue**: Custom sections might not update when content changes
**Solution**: Ensure sections query fresh data, not cached

### 4. Mobile Layout Breaking
**Issue**: Custom grid might not stack properly on mobile
**Solution**: Use Kirby's built-in responsive column behavior, test on real devices

### 5. Button Dialogs Not Opening
**Issue**: Custom "New Post/Project" buttons might not trigger Panel dialogs
**Solution**: Use Kirby's Panel API methods (`panel.dialog.open`), follow documentation examples

### 6. Performance with Large Content
**Issue**: Querying recent content might be slow with many pages
**Solution**: Limit queries, add pagination if needed, consider caching section data

### 7. User Permissions
**Issue**: Some users might not have permission to create posts/projects
**Solution**: Check user permissions before showing buttons, gracefully handle unauthorized actions

### 8. Time Zone Issues
**Issue**: Greeting might show wrong time of day for different time zones
**Solution**: Use server time consistently, or consider user's browser time zone

---

## Dependencies

**Required:**
- Kirby 4.x (current version)
- Existing plugin: `site/plugins/site/` (already exists)

**Optional:**
- None - all features use Kirby core

---

## Testing Strategy

### Unit Testing
- Section data queries return correct results
- Time-based greeting calculates correctly
- User name extraction works

### Integration Testing
- Sections integrate into dashboard
- Layout responds correctly
- Buttons trigger correct actions

### User Acceptance Testing
- Dashboard is intuitive
- Actions work as expected
- Mobile experience is smooth

---

## Success Criteria

**Must Have:**
- [ ] 2-column desktop layout
- [ ] Single-column mobile layout
- [ ] Working greeting with user name
- [ ] Functional "New Post" and "New Project" buttons
- [ ] Recent posts display (published + drafts)
- [ ] Recent projects display
- [ ] All links navigate correctly

**Nice to Have:**
- [ ] Typography perfectly matches public site
- [ ] Smooth animations/transitions
- [ ] Loading states for sections
- [ ] Error handling for failed queries

**Portfolio Quality:**
- [ ] Clean, well-documented code
- [ ] Responsive and accessible
- [ ] Professional appearance
- [ ] Scalable architecture

---

## Timeline

| Phase | Duration | Total Time |
|-------|----------|------------|
| Phase 1: Setup | 15 min | 0:15 |
| Phase 2: Greeting & Actions | 45 min | 1:00 |
| Phase 3: Recent Content | 1 hour | 2:00 |
| Phase 4: Layout & Grid | 45 min | 2:45 |
| Phase 5: Testing & Polish | 30 min | 3:15 |

**Total Estimated Time**: 3 hours 15 minutes

---

## Future Enhancements

### Short Term
- Add site statistics (total posts, projects, pages)
- Display last published date
- Add quick links to commonly edited pages

### Medium Term
- Analytics widget (page views, popular posts)
- Draft reminder notifications
- Calendar view for scheduled posts

### Long Term
- Custom workflow management
- Content approval system
- Multi-user collaboration features

---

## References

- [Kirby Panel Sections](https://getkirby.com/docs/reference/plugins/extensions/panel-sections)
- [Advanced Panel Area](https://getkirby.com/docs/cookbook/panel/advanced-panel-area)
- [Site Blueprint](https://getkirby.com/docs/reference/panel/blueprints/site)
- [Structuring Blueprints](https://getkirby.com/docs/guide/blueprints/layout)

---

**Plan Created**: 2026-03-29
**Last Updated**: 2026-03-29
**Status**: Ready for Review
