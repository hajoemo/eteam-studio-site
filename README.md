# eteam-studio-works — WonderCMS theme

A dark, editorial one-page portfolio theme converted from your static
`index8.html` design, built following WonderCMS's
["Create theme in 8 easy steps"](https://github.com/WonderCMS/wondercms/wiki/Create-theme-in-8-easy-steps)
guide and the [Theme tags](https://github.com/WonderCMS/wondercms/wiki/Theme-tags) reference.

## What's in this package

```
eteam-studio-works/
├── theme.php            ← theme layout + all required WonderCMS tags
├── css/
│   └── style.css         ← all styling (ported 1:1 from your design)
├── wcms-modules.json     ← theme metadata (for sharing/installing as a module)
├── page-content.html     ← the actual homepage content ("the page")
└── img/
    └── (empty)            put a cover.jpg here if you plan to share this
                            as a module — referenced by wcms-modules.json
```

## How theme.php maps to the 8 steps

| Step | What it does | Where |
|---|---|---|
| 1 | `<head>` SEO tags, admin CSS, theme `style.css` | top of `theme.php` |
| 2 | `$Wcms->settings()` / `$Wcms->alerts()` | right after `<body>` |
| 3 | `$Wcms->menu()` | inside `<header><nav>` |
| 4 | `$Wcms->siteTitle()` | header logo/site title, linked with `$Wcms->url()` |
| 5 | `$Wcms->page('content')` | main editable area — this is where `page-content.html` goes |
| 6 | `$Wcms->block('subside')` | optional site-wide note, shown on every page if not empty |
| 7 | `$Wcms->footer()` | `<footer>` |
| 8 | `$Wcms->js()` | just before `</body>`, after the theme's own scrollspy/lightbox script |

The scrollspy + lightbox JavaScript and the lightbox markup from your
original file live in `theme.php` (not the page content) since they're
global, reusable behaviour — they work on any page that contains
`.tile` elements and `section.gallery[id]` sections.

## Installation

1. Copy the whole `eteam-studio-works` folder into your WonderCMS
   install at `themes/eteam-studio-works/`.
2. Log in to the admin panel and set this as the active theme
   (Settings → Theme).
3. Open the homepage in the admin, switch the content editor to
   **source code view**, and paste in the contents of
   `page-content.html`.
4. Upload your images through the admin **File manager** — they will
   land in `/files/`. `page-content.html` already references them as
   `files/02_Puppetheads_carved2s.jpg`, `files/feet.jpg`,
   `files/PuppetPlay_Matrix_Wenlin_26-9.jpeg`,
   `files/PuppetPlay_Matrix_Wenlin_26-5.jpeg`,
   `files/PuppetPlay_Matrix_Wenlin_26-4.jpeg` and
   `files/Videostill_10.jpeg` — upload files with those exact names,
   or edit the `src` attributes to match whatever you upload.
5. Save. Because the content includes iframes and inline `style`
   attributes (used for the `--ratio` custom property on image tiles),
   always re-edit through the source-code view rather than the visual
   WYSIWYG toolbar — some visual editors strip custom attributes on
   save.

## Notes on the conversion

- **Navigation**: your original nav was an in-page anchor list
  (`#puppet-heads`, `#hand-went`, …) for scrolling within one long
  page. WonderCMS's `$Wcms->menu()` tag generates links between
  *separate CMS pages*, not anchors within one page, so it's used in
  the header for standard site navigation (if you ever add an About or
  Contact page). The section jump-links from your design now live
  inside the page content itself, as `.section-nav`, right under the
  hero — since they only make sense on this specific one-page layout.
- **Vimeo embeds** are left exactly as in your original file — no
  video hosting changes needed.
- **No image files were included** in your upload, so `page-content.html`
  references the original filenames under `/files/` — upload the actual
  JPEGs through the WonderCMS admin before publishing.
- `wcms-modules.json` is optional but included so you can share this
  theme as an installable module later, per the
  [Custom modules](https://github.com/WonderCMS/wondercms/wiki/Custom-modules)
  guide — fill in `authorName`/`authorUrl` and drop a `cover.jpg` into
  `img/` if you do.
