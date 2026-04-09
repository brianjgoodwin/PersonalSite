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
- **[Kirby Git Content](https://github.com/thathoff/kirby-git-content)** -- Auto-commits Panel changes to git
- **Custom Site Plugin** -- Panel dashboard and cache invalidation hooks
- **Cloudflare** -- CDN and caching layer
- **nginx** -- Web server
- **GitHub Actions** -- CI/CD (deploy, link checking, feed validation)

## Local Development

1. Clone this repository
2. Run `composer install` to install dependencies
3. Run `composer start` to start the development server at http://localhost:8000

Git content syncing is automatically disabled on localhost.

## Project Structure

```
assets/            CSS, JS (menu, gallery, breadcrumb nav, procedural art)
content/           File-based content (text files and media)
site/
  blueprints/      Panel field definitions
  config/          Kirby configuration (routes, caching, security, git plugin)
  plugins/         Site plugin (dashboard, cache hooks) + git-content
  snippets/        Reusable components (header, footer, gallery, feed, sitemap)
  templates/       Page templates (home, about, article, posts, projects, links)
```

## Deployment

Merging to `main` triggers a GitHub Actions workflow that:

1. Deploys code to the production server via SSH
2. Clears Kirby, nginx, and Cloudflare caches
3. Warms the page cache by crawling the sitemap

See [`.github/DEPLOYMENT.md`](.github/DEPLOYMENT.md) for setup details.

## License

- Site code: Custom templates and configuration
- CMS: [Kirby License](https://getkirby.com/license)
