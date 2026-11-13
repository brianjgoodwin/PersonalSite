# brianjgoodwin.net

My personal website built with [Kirby CMS](https://getkirby.com) (v5).

## Features

- **Magazine Layout** -- Swiss-inspired design with narrow text columns and wide images
- **Procedural Art** -- Canvas-based generative geometric art sidebar, seeded by page URL for consistency
- **Image Gallery** -- Horizontal scroll gallery with modal viewer
- **Posts** -- Articles with draft/published workflow, date sorting, and RSS feed
- **Projects** -- Portfolio section for showcasing work
- **Content Sections** -- Home, About, Posts, Projects, Links, Now, and Privacy pages
- **Accessibility** -- Skip navigation, ARIA landmarks, keyboard focus indicators
- **Responsive Design** -- Mobile-friendly with hamburger menu
- **Caching** -- Page caching with automatic invalidation on content changes, smart HTTP cache headers per content type
- **Security Headers** -- CSP, X-Frame-Options, XSS protection, referrer policy
- **SEO** -- Sitemap.xml, RSS/Atom feed, robots.txt (blocks AI scrapers, allows major search engines)

## Tech Stack

- **[Kirby CMS 5](https://getkirby.com)** -- File-based CMS (PHP 8.2+)
- **Custom Site Plugin** -- Panel dashboard and cache invalidation hooks
- **Cloudflare** -- CDN and caching layer
- **nginx** -- Web server
- **GitHub Actions** -- link checking and feed validation only (no deploy)

## Local Development

1. Clone this repository
2. Run `composer install` -- **required**, not optional. Kirby itself lives in
   `kirby/` and is not committed, so a fresh clone has no CMS until composer
   installs it. Without this step `index.php` fails on
   `require 'kirby/bootstrap.php'`.
3. Run `composer start` to start the development server at http://localhost:8000

`composer start` runs `php -S localhost:8000 kirby/router.php`. Use that
router as the front controller for any hand-rolled `php -S` -- pointing it at
`index.php` instead breaks Kirby's routing in ways that look like template
bugs.

To test on another device on the LAN, bind to the machine's actual LAN IP
rather than `0.0.0.0`; Kirby builds absolute asset URLs from the bind address,
and `0.0.0.0` is not routable from the other device, so the page loads
unstyled:

```bash
php -S 192.168.x.x:8000 kirby/router.php
```

To view draft content while working on layout, use the dev-only route:
`/draft/posts/<slug>` (not registered in production).

## Content workflow

**Panel edits on the production server are not committed to git.** There is no
automatic commit or push. The `thathoff/kirby-git-content` plugin used to do
this and was removed -- its auto-commits produced messages like
`update(page): links` that carried no information, and having content in git
delivered none of git's actual benefits (no meaningful diffs, no branching, no
reverting a typo fix).

**`content/` is not in version control.** The production server is the source
of truth. Git holds the layer between core Kirby and content -- templates,
snippets, blueprints, CSS, config.

### Where content actually lives

| | |
| --- | --- |
| **Source of truth** | the production server, edited via the Panel |
| **Backup** | `content_backups/`, an rsync mirror pulled nightly at 03:00 |
| **In git** | nothing |

Three local scripts handle it. They live in `scripts/` and are **gitignored**
-- not in this repo, because they contain server paths and hostnames. See
`scripts/README.md` for details.

- `sync-blog.sh` -- pulls `content/` from production to `content_backups/`.
  Scheduled by launchd, daily 03:00. Notifies on failure.
- `check-blog-sync.sh` -- watchdog, daily 15:00. Notifies if the last
  successful sync is over 36 hours old. This catches the failure mode a
  backup script cannot catch itself: never having run (Mac asleep, network
  down).
- `restore-blog.sh` -- manual only, never scheduled. Pushes
  `content_backups/` back up, overwriting the server. Supports `--dry-run`
  and requires typing `YES`.

### Consequences to remember

**A fresh clone has no content.** It will boot and render a site with no
pages. To work on templates locally, copy `content/` down from the server or
from `content_backups/`.

**Panel edits are not versioned at all.** There is no history, no diff, no
revert. If you need to undo a content change, the backup mirror is the only
way back, and it is at most 24 hours old.

**`_changes/` directories are not backed up, by design.** Those are the
Panel's unsaved-draft buffers -- transient editor state, not content. The site
renders fine without them; this was tested, not assumed.

## Project Structure

```
assets/            CSS, JS (menu, gallery, breadcrumb nav, procedural art)
site/
  blueprints/      Panel field definitions
  config/          Kirby configuration (routes, caching, security)
  plugins/         Site plugin (dashboard, cache hooks)
  snippets/        Reusable components (header, footer, gallery, feed, sitemap)
  templates/       Page templates (home, about, article, posts, projects, links)
index.php          Front controller -- requires kirby/bootstrap.php

NOT IN GIT
  kirby/           Kirby CMS       -- composer install
  vendor/          Dependencies    -- composer install
  content/         Site content    -- server is source of truth; see above
  content_backups/ rsync mirror    -- scripts/sync-blog.sh
  scripts/         Backup scripts  -- local only, contain server details
  media/           Kirby thumbs    -- regenerated on demand
  archive/         Old design sketches
```

## Untested code paths

**Two features are fully wired but have never actually rendered**, because no
content exercises them. They are not dead code -- the blueprint field, the
template branch and the CSS all exist and connect. They have simply never been
seen in a browser, so treat them as unverified rather than working.

| Feature | Wired at | Why it never renders |
| --- | --- | --- |
| **One-column project layout** | `site/blueprints/pages/project.yml` (`layout` select) -> `site/templates/project.php:7` -> `.site-main.layout-one-column` in `assets/css/index.css` | Both projects are set to `Layout: two-column`. Nothing has selected one-column. |
| **Project image gallery** | `site/blueprints/pages/project.yml` (`gallery` files section) -> `site/templates/project.php:23` -> `site/snippets/gallery.php` | The template branch requires images whose **file template is `gallery-image`**. Both project images are cover images, so the branch is never entered. |

If you use either one for real, **look at the result before deploying** -- it is
first-run code. To test: set a project to `Layout: one-column`, or upload an
image into the Panel's "Gallery Images" section (not the cover image field,
which uses a different file template).

Do not delete these on the assumption that they are unused. A code review once
flagged the one-column CSS as unreachable; it is reachable, just unselected.

## Updating the site

**There is no automated deploy.** Updating is a manual `git pull` on the
server, by design -- see "Why no pipeline" below.

```bash
ssh <user>@<server>
cd /var/www/brianjgoodwin.net        # wherever the site lives

git pull origin main
composer install --no-dev --optimize-autoloader
rm -rf site/cache/*                 # Kirby caches rendered pages
```

`composer install` is required because `kirby/` is **not** in this repo (see
below). Skip it and nothing breaks *unless* `composer.lock` changed -- but
running it is cheap and idempotent, so just run it.

### The remote must be HTTPS, not SSH

```bash
git remote set-url origin https://github.com/brianjgoodwin/PersonalSite.git
```

This repo is public, so HTTPS pulls need no credentials at all. SSH
(`git@github.com:...`) **always** demands a key even for public repos --
there is no anonymous SSH -- and on a server that means every user who
might pull (you, root via sudo, the web server user running the Panel)
needs its own key and its own `known_hosts`. That is what produced
`Host key verification failed` from the Panel and
`Permission denied (publickey)` from `sudo git pull`: three identities,
one key, in the wrong home directory.

HTTPS sidesteps all of it.

### Why no pipeline

There used to be a GitHub Actions deploy: SSH in, `git reset --hard`,
`composer install`, purge nginx and Cloudflare, warm the cache from the
sitemap. It was a good thing to learn and it is kept in git history.

It was removed because it was load-bearing infrastructure for a personal
blog that gets edited rarely. Its last two runs failed in April 2026 and
nobody noticed for seven months, which is the whole argument: a pipeline
you do not watch is not reliability, it is a second thing that can break.
A manual pull fails loudly, in front of you, while you are already
looking at the terminal.

The `git reset --hard origin/main` in that workflow was also a standing
hazard -- it would have deleted server-side content the moment `content/`
left version control. Removing the pipeline removes that trap.

## What is and is not in this repo

**Tracked: 59 files.** `assets/` (CSS, JS), `site/` (templates, snippets,
blueprints, config), two GitHub workflows, and the root-level README and
composer manifests. That is the whole repo -- just the parts written by hand.

It was 785 files before this cleanup. The difference was Kirby itself and the
site's content, neither of which belonged here.

**Not tracked:**

| Path | Why | How it gets there |
| --- | --- | --- |
| `kirby/` | Kirby CMS itself, ~680 files of build output | `composer install` |
| `vendor/` | Composer dependencies | `composer install` |
| `content/` | Server is the source of truth | rsync from server |
| `content_backups/` | The backup mirror itself | `scripts/sync-blog.sh` |
| `scripts/` | Backup scripts -- hold server paths and hostnames | local only |
| `media/` | Kirby's generated thumbnails | regenerated on demand |
| `archive/` | Old design sketches, kept for reference | local only |
| `site/accounts/`, `site/sessions/`, `site/cache/` | Runtime state | created at runtime |
| `site/config/.license` | Kirby license key -- **never commit** | manual |

`kirby/` used to be committed. It was 678 tracked files that
`composer install` overwrote on every deploy, which meant the repo claimed
Kirby 5.2.0 while `composer.lock` specified 5.5.3 and the server ran
whatever composer last installed. It also generated most of this repo's
Dependabot noise and vulnerability alerts. Untracking it makes
`composer.lock` the single authority on which Kirby version runs.

## License

- Site code: Custom templates and configuration
- CMS: [Kirby License](https://getkirby.com/license)
