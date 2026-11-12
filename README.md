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
- **Automated Deployment** -- GitHub Actions pipeline: deploy via SSH, clear nginx/Cloudflare caches, warm cache from sitemap

## Tech Stack

- **[Kirby CMS 5](https://getkirby.com)** -- File-based CMS (PHP 8.2+)
- **Custom Site Plugin** -- Panel dashboard and cache invalidation hooks
- **Cloudflare** -- CDN and caching layer
- **nginx** -- Web server
- **GitHub Actions** -- CI/CD (deploy, link checking, feed validation)

## Local Development

1. Clone this repository
2. Run `composer install` to install dependencies
3. Run `composer start` to start the development server at http://localhost:8000

To view draft content while working on layout, use the dev-only route:
`/draft/posts/<slug>` (not registered in production).

## Content workflow

**Panel edits on the production server are not committed to git.** There is no
automatic commit or push. The `thathoff/kirby-git-content` plugin used to do
this and was removed -- its auto-commits produced messages like
`update(page): links` that carried no information, and having content in git
delivered none of git's actual benefits (no meaningful diffs, no branching, no
reverting a typo fix).

**Current state, as of this commit:** content is *still tracked in git*, but
nothing commits it automatically. If you edit in the production Panel, that
change lives only on the server until you deliberately bring it down.

**Intended end state:** content leaves version control entirely and is covered
by separate backups. Git stays for the layer between core Kirby and content --
templates, snippets, blueprints, CSS, config.

That move is deliberately **not done yet**, because it has a prerequisite:

> `.github/workflows/deploy.yml` runs `git reset --hard origin/main` on the
> server. The moment `content/` stops being tracked, the next deploy deletes
> every content file that git no longer knows about. The deploy has to stop
> managing `content/` *before* content leaves the index.

The clean fix is the release-directory layout sketched in
`.github/workflows/deploy-releases.yml.draft`, where `content/` is a symlink
into a `shared/` directory that deploys cannot reach. That is part of the
planned server rebuild.

**Before content leaves git, backups must exist and a restore must be
verified.** Right now GitHub is an accidental offsite copy of content; removing
content from git removes that copy, silently. Content is ~750K, so this is not
a hard problem -- it just has to actually be done first.

## Project Structure

```
assets/            CSS, JS (menu, gallery, breadcrumb nav, procedural art)
content/           File-based content (text files and media)
site/
  blueprints/      Panel field definitions
  config/          Kirby configuration (routes, caching, security)
  plugins/         Site plugin (dashboard, cache hooks)
  snippets/        Reusable components (header, footer, gallery, feed, sitemap)
  templates/       Page templates (home, about, article, posts, projects, links)
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

## Deployment

Merging to `main` triggers a GitHub Actions workflow that:

1. Deploys code to the production server via SSH
2. Clears Kirby, nginx, and Cloudflare caches
3. Warms the page cache by crawling the sitemap

See [`.github/DEPLOYMENT.md`](.github/DEPLOYMENT.md) for setup details.

## License

- Site code: Custom templates and configuration
- CMS: [Kirby License](https://getkirby.com/license)
