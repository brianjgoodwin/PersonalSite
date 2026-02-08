# Documentation Panel Feature - Implementation Plan

## Overview
Create a Panel-only documentation area with three sections: Developer, Style Guide, and Brand. Uses Kirby's native pages system with custom blueprints for wiki-style content management.

## Key Principles
- **Backend only**: No frontend templates or UI changes
- **Panel-only content**: Pages remain unlisted and inaccessible from site
- **Testable increments**: Each phase can be verified independently
- **Rollback-safe**: Each phase can be undone without affecting others

---

## Phase 1: Foundation Setup
**Goal**: Create the main Documentation page structure and basic blueprint

### Tasks
1. Create blueprint: `site/blueprints/pages/documentation.yml`
   - Set up basic page structure
   - Add title field
   - Add info section explaining this is Panel-only
   - Configure options (status: unlisted, changeTemplate: false)

2. Create content directory structure
   - Create `/content/documentation/` folder
   - Add `documentation.txt` with basic metadata
   - Set page status to unlisted

### Testing Criteria
- [ ] Documentation appears in Panel pages list
- [ ] Page shows "unlisted" status
- [ ] Info section displays correctly in Panel
- [ ] Page does NOT appear in site frontend navigation
- [ ] Direct URL access returns 404 or appropriate error

### Files Created
- `site/blueprints/pages/documentation.yml`
- `content/documentation/documentation.txt`

### Rollback
Delete the two files created above

---

## Phase 2: Add Child Page Structure
**Goal**: Enable three documentation sections as child pages

### Tasks
1. Update `documentation.yml` blueprint
   - Add `pages` section with card layout
   - Configure to show child pages as cards
   - Set allowed templates: `documentation-section`
   - Add help text for creating sections

2. Create child page blueprint: `site/blueprints/pages/documentation-section.yml`
   - Basic structure with title field
   - Placeholder for content (temporary textarea)
   - Set options (status: unlisted, changeTemplate: false)

3. Create three child pages via Panel
   - Developer section
   - Style Guide section
   - Brand section

### Testing Criteria
- [ ] Three cards appear on Documentation page in Panel
- [ ] Cards display with appropriate icons/titles
- [ ] Clicking card opens child page editor
- [ ] Child pages show in unlisted state
- [ ] Child pages NOT accessible from frontend
- [ ] Can add/edit basic content in each section

### Files Created/Modified
- Modified: `site/blueprints/pages/documentation.yml`
- Created: `site/blueprints/pages/documentation-section.yml`
- Created: `content/documentation/developer/documentation-section.txt`
- Created: `content/documentation/style-guide/documentation-section.txt`
- Created: `content/documentation/brand/documentation-section.txt`

### Rollback
- Revert `documentation.yml` to Phase 1 version
- Delete `documentation-section.yml`
- Delete three child page folders

---

## Phase 3: Enhance Content Editing
**Goal**: Implement rich wiki-style editor using writer field

### Tasks
1. Update `documentation-section.yml` blueprint
   - Replace textarea with writer field
   - Configure writer field with appropriate marks and nodes:
     - Marks: bold, italic, underline, code, link
     - Nodes: heading, paragraph, bullet list, ordered list, code block
   - Add optional fields: last updated, author (if needed)

2. Test content creation
   - Add sample formatted content to each section
   - Test all formatting options work correctly
   - Verify readability in Panel

### Testing Criteria
- [ ] Writer field displays with formatting toolbar
- [ ] Can create headings (H2, H3, H4)
- [ ] Can create lists (bulleted and numbered)
- [ ] Can add links and format text (bold, italic)
- [ ] Can add code blocks with syntax highlighting
- [ ] Content displays in readable, formatted view when not editing
- [ ] Click-to-edit works smoothly

### Files Modified
- Modified: `site/blueprints/pages/documentation-section.yml`

### Rollback
Revert `documentation-section.yml` to Phase 2 version (will preserve existing content)

---

## Phase 4: Polish and Metadata
**Goal**: Add helpful metadata and organizational features

### Tasks
1. Enhance `documentation.yml`
   - Add custom icon for Documentation page
   - Add sections for statistics (page count, last updated)
   - Refine card layout styling if needed

2. Enhance `documentation-section.yml`
   - Add metadata sidebar:
     - Last Updated (auto-populated)
     - Table of Contents (optional)
     - Related docs (optional tags field)
   - Add info section with editing guidelines

3. Add Panel access shortcuts
   - Consider adding to Site blueprint as quick link
   - Or custom Panel menu item (simple PHP config)

### Testing Criteria
- [ ] Custom icon displays correctly
- [ ] Metadata fields populate automatically
- [ ] Info sections display helpful guidance
- [ ] Quick access works from Panel dashboard (if implemented)
- [ ] All previous phase features still work

### Files Modified
- Modified: `site/blueprints/pages/documentation.yml`
- Modified: `site/blueprints/pages/documentation-section.yml`
- Possibly modified: `site/blueprints/site.yml` (for quick links)

### Rollback
Revert modified blueprints to Phase 3 versions

---

## Phase 5: Content Migration (Optional)
**Goal**: Migrate any existing documentation from other sources

### Tasks
1. Review existing docs (if any)
   - README files
   - Planning documents
   - Style guides
   - Brand guidelines

2. Format and migrate content
   - Convert markdown to writer field format
   - Organize into appropriate sections
   - Add relevant metadata

### Testing Criteria
- [ ] All migrated content displays correctly
- [ ] Formatting preserved from original docs
- [ ] Links work correctly
- [ ] Images (if any) display properly

### Files Modified
- Content files in documentation sections

### Rollback
Remove migrated content, keep structure

---

## Technical Notes

### Why This Approach Works
1. **No frontend impact**: Unlisted pages never appear on site
2. **Git-friendly**: All content in `/content/` syncs via git-content plugin
3. **Native Kirby**: Uses built-in Panel features, no custom code
4. **Scalable**: Easy to add more sections or nest deeper

### Security Considerations
- Pages require Panel authentication to view
- No special permissions needed (uses existing Panel auth)
- Content version controlled via git-content plugin

### Performance Impact
- Minimal: Only 3-4 additional pages
- No frontend rendering overhead
- Panel performance unaffected

---

## Success Criteria

### Must Have
- [ ] Three documentation sections accessible in Panel
- [ ] Wiki-style editor with formatting options
- [ ] Content is readable without switching to edit mode
- [ ] Panel-only (not visible on frontend)
- [ ] Git-tracked content

### Nice to Have
- [ ] Quick access from Panel dashboard
- [ ] Automatic table of contents
- [ ] Search within documentation (may require custom implementation)
- [ ] Version history visible in Panel

---

## Time Estimates

- **Phase 1**: 10-15 minutes
- **Phase 2**: 15-20 minutes
- **Phase 3**: 10-15 minutes
- **Phase 4**: 15-20 minutes
- **Phase 5**: Variable (depends on content volume)

**Total**: ~1 hour for core implementation (Phases 1-4)

---

## Implementation Status

### Current Session: 2026-01-13

**STATUS: Phase 3 - IN PROGRESS (Debugging writer field rendering issue)**

#### Phases Completed:
- ✅ **Phase 1**: COMPLETE - Foundation setup with route blocking frontend access
- ✅ **Phase 2**: COMPLETE - Child page structure created (Developer, Style Guide, Brand sections)
- ⚠️ **Phase 3**: IN PROGRESS - Writer field configured but not rendering in Panel

#### Current Issue:
The writer field in `documentation-section.yml` is not rendering an editable interface in the Panel. The content displays as plain text with help text visible, but no formatting toolbar or clickable editor area appears.

**What we've tried:**
1. Added `paragraph` node (required for writer field)
2. Set `inline: false` explicitly
3. Restructured blueprint to use `columns > sections > fields` pattern (proper Kirby structure)
4. Tested in private browser window (ruled out caching)

**Current Blueprint Configuration:**
```yaml
columns:
  main:
    width: 2/3
    sections:
      content:
        type: fields
        fields:
          content:
            label: Documentation Content
            type: writer
            inline: false
            marks: [bold, italic, underline, code, link]
            nodes: [paragraph, heading, bulletList, orderedList, codeBlock]
```

**Next debugging steps:**
1. Check browser console for JavaScript errors
2. Verify Kirby version supports writer field
3. Try simplifying writer field config (minimal marks/nodes)
4. Consider using textarea temporarily and revisiting writer field
5. Check if writer field requires additional Kirby plugins

**Files Modified This Session:**
- `site/blueprints/pages/documentation.yml` - Added pages section + route blocking
- `site/config/config.php` - Added route: `documentation(.*)` returns 404
- `site/blueprints/pages/documentation-section.yml` - Multiple iterations to fix writer field
- `content/documentation/developer/documentation-section.txt` (created)
- `content/documentation/style-guide/documentation-section.txt` (created)
- `content/documentation/brand/documentation-section.txt` (created)

---

## Next Session Prompt

Continue implementation with this prompt:

```
I'm continuing the Documentation Panel feature implementation. We're debugging
Phase 3 (writer field not rendering). Please review the "Implementation Status"
section in DOCUMENTATION-FEATURE-PLAN.md to see what we've tried and continue
troubleshooting the writer field rendering issue.

Once the writer field is working, we can proceed to Phase 4.
```

---

## Questions for Review

Before starting implementation, consider:

1. **Content Organization**: Do you want nested sections (e.g., Developer > API > Endpoints)?

ANSWER: yes

2. **Access Control**: Should different users see different sections? (probably no for personal site)

ANSWER: no (you were correct)

3. **Search**: Important for finding docs? (would require Phase 6)

ANSWER: yes, but that can wait

4. **Export**: Need to export docs to PDF/other formats? (would require Phase 6)

ANSWER: No

5. **Versioning**: Want to see content history in Panel beyond git? (may need plugin)

ANSWER: No, git is fine

---

**Plan Created**: 2026-01-12
**Kirby Version**: 4.x (compatible)
**Estimated Completion**: 1-2 hours including testing
